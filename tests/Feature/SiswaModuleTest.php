<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Imports\SiswaImport;

class SiswaModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role 'admin' dan daftarkan permission
        $adminRole = Role::create(['name' => 'admin']);
        $permissions = [
            'view siswa',
            'create siswa',
            'edit siswa',
            'delete siswa',
            'import siswa',
        ];
        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }
        $adminRole->givePermissionTo($permissions);

        // Buat user dan assign role admin
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Nonaktifkan hanya middleware Spatie role & permission
        $this->withoutMiddleware([
            RoleMiddleware::class,
            PermissionMiddleware::class,
        ]);
    }

    public function test_guest_is_redirected_from_siswa_routes(): void
    {
        $this->get('/siswa')->assertRedirect('/login');
        $this->get('/siswa/create')->assertRedirect('/login');
        $this->post('/siswa', [])->assertRedirect('/login');
        $this->post('/siswa/import', [])->assertRedirect('/login');
    }

    public function test_admin_can_view_siswa_index(): void
    {
        // Siapkan data Tahun Ajaran & Kelas & Siswa
        $ta = TahunAjaran::create([
            'nama' => '2025/2026',
            'semester' => 'Ganjil',
            'aktif' => true,
        ]);

        $kelas = Kelas::create([
            'nama' => 'X IPA',
            'kapasitas' => 30,
            'tahun_ajaran_id' => $ta->id,
        ]);

        Siswa::create([
            'nis' => '12345',
            'nisn' => null,
            'nama_depan' => 'John',
            'nama_belakang' => 'Doe',
            'email' => 'john@example.com',
            'kelas_id' => $kelas->id,
        ]);

        $response = $this->actingAs($this->admin)->get('/siswa');

        $response->assertStatus(200);
        $response->assertSee('12345');
        $response->assertSee('John Doe');
    }

    public function test_admin_can_create_update_and_delete_siswa(): void
    {
        $ta = TahunAjaran::create([
            'nama' => '2025/2026',
            'semester' => 'Genap',
            'aktif' => true,
        ]);

        $kelas = Kelas::create([
            'nama' => 'XI IPS',
            'kapasitas' => 25,
            'tahun_ajaran_id' => $ta->id,
        ]);

        $data = [
            'nis' => '67890',
            'nisn' => '09876',
            'nama_depan' => 'Jane',
            'nama_belakang' => 'Smith',
            'email' => 'jane@example.com',
            'kelas_id' => $kelas->id,
        ];

        // Create
        $this->actingAs($this->admin)
            ->post('/siswa', $data)
            ->assertRedirect('/siswa');
        $this->assertDatabaseHas('siswa', ['nis' => '67890']);

        $siswa = Siswa::where('nis', '67890')->first();

        // Update
        $data['nama_depan'] = 'Janet';
        $this->actingAs($this->admin)
            ->put("/siswa/{$siswa->id}", $data)
            ->assertRedirect('/siswa');
        $this->assertDatabaseHas('siswa', [
            'nis' => '67890',
            'nama_depan' => 'Janet',
        ]);

        // Delete
        $this->actingAs($this->admin)
            ->delete("/siswa/{$siswa->id}")
            ->assertRedirect();
        $this->assertDatabaseMissing('siswa', ['nis' => '67890']);
    }

    public function test_admin_can_import_siswa_via_excel(): void
    {
        Excel::fake();

        $file = UploadedFile::fake()->create(
            'siswa.xlsx',
            10,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        $this->actingAs($this->admin)
            ->post('/siswa/import', ['file' => $file])
            ->assertRedirect('/siswa');

        Excel::assertImported(SiswaImport::class);
    }
}
