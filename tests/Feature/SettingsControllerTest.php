<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use ZipArchive;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_update_persists_settings_and_flashes_success(): void
    {
        $data = [
            'app_name' => 'My App',
            'app_url' => 'https://example.com',
            'midtrans_server_key' => 'srv',
            'midtrans_client_key' => 'cli',
            'mail_mailer' => 'smtp',
            'mail_host' => 'mail.example.com',
            'mail_port' => 587,
            'mail_username' => 'user',
            'mail_password' => 'pass',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'admin@example.com',
            'mail_from_name' => 'Admin',
            'activation_key' => 'key',
            'backup_frequency' => 'daily',
            'backup_max_files' => 5,
        ];

        $this->actingAs($this->admin)
            ->post('/settings', $data)
            ->assertRedirect()
            ->assertSessionHas('success', 'Pengaturan berhasil diperbarui.');

        foreach ($data as $key => $value) {
            $this->assertDatabaseHas('settings', [
                'key' => $key,
                'value' => (string) $value,
            ]);
        }
    }

    public function test_backup_runs_artisan_and_flashes_success(): void
    {
        Storage::fake('local');
        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run --only-db')
            ->andReturn(0);

        $this->actingAs($this->admin)
            ->post('/settings/backup')
            ->assertRedirect()
            ->assertSessionHas('success', 'Backup database selesai.');
    }

    public function test_restore_restores_sql_from_backup_zip(): void
    {
        Storage::fake('local');
        DB::shouldReceive('unprepared')->once();

        $folder = config('backup.backup.name');
        $file = $folder . '/test.zip';
        $path = Storage::disk('local')->path($file);
        \File::ensureDirectoryExists(dirname($path));

        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE);
        $zip->addFromString('dump.sql', 'SELECT 1;');
        $zip->close();

        $this->actingAs($this->admin)
            ->post('/settings/restore', ['file' => $file])
            ->assertRedirect()
            ->assertSessionHas('success', 'Restore database berhasil.');
    }

    public function test_restore_upload_with_invalid_file_shows_error(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('dump.sql', 10);

        $this->actingAs($this->admin)
            ->from('/settings')
            ->post('/settings/restore-upload', ['backup_file' => $file])
            ->assertRedirect('/settings')
            ->assertSessionHasErrors([
                'backup_file' => 'The backup file must be a file of type: zip.',
            ]);
    }
}
