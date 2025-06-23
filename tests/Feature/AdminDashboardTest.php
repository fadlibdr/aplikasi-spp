<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Event;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            RoleMiddleware::class,
            PermissionMiddleware::class,
        ]);

        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_dashboard_displays_payment_table(): void
    {
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
            'nis' => '001',
            'nisn' => null,
            'nama_depan' => 'Test',
            'nama_belakang' => 'User',
            'email' => 'test@example.com',
            'kelas_id' => $kelas->id,
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200)
                 ->assertSee('id="paymentChart"', false)
                 ->assertSee('Status Pembayaran Siswa');
    }

    public function test_calendar_contains_events(): void
    {
        Event::create([
            'title' => 'Ujian Akhir',
            'start_date' => '2025-07-01',
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200)
                 ->assertSee('Ujian Akhir');
    }
}

