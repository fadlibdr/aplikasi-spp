<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Middleware\RoleMiddleware;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->withoutMiddleware([
            RoleMiddleware::class,
        ]);
    }

    public function test_dashboard_displays_payment_chart(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200)
                 ->assertSee('id="paymentChart"', false);
    }

    public function test_dashboard_displays_payment_status_table(): void
    {
        // Setup minimal data: tahun ajaran, kelas, siswa, jenis pembayaran, iuran
        $ta = \App\Models\TahunAjaran::create([
            'nama' => '2025/2026',
            'semester' => 'Ganjil',
            'aktif' => true,
        ]);

        $kelas = \App\Models\Kelas::create([
            'nama' => 'X IPA',
            'kapasitas' => 30,
            'tahun_ajaran_id' => $ta->id,
        ]);

        $siswa = \App\Models\Siswa::create([
            'nis' => '001',
            'nama_depan' => 'Test',
            'nama_belakang' => 'User',
            'kelas_id' => $kelas->id,
            'email' => 'test@example.com',
        ]);

        $jenis = \App\Models\JenisPembayaran::create([
            'kode' => 'SPP',
            'nama' => 'SPP',
            'nominal' => 100000,
            'frekuensi' => 'Bulanan',
        ]);

        \App\Models\Iuran::create([
            'siswa_id' => $siswa->id,
            'jenis_pembayaran_id' => $jenis->id,
            'bulan' => 1,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200)
                 ->assertSee('Status Pembayaran Siswa')
                 ->assertSee('Test User');
    }
}
