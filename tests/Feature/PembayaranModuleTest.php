<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\JenisPembayaran;
use App\Models\Iuran;
use App\Models\Pembayaran;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class PembayaranModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable Spatie role/permission middleware for this test
        $this->withoutMiddleware([
            RoleMiddleware::class,
            PermissionMiddleware::class,
        ]);

        // Create 'admin' role and relevant permissions
        $role = Role::create(['name' => 'admin']);
        $perms = [
            'view pembayaran',
            'create pembayaran',
        ];
        foreach ($perms as $name) {
            Permission::create(['name' => $name]);
        }
        $role->givePermissionTo($perms);

        // Create and authenticate an admin user
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Login');
    }

    public function test_guest_is_redirected_from_pembayaran_routes(): void
    {
        $this->get('/pembayaran')->assertRedirect('/login');
        $this->get('/pembayaran/create')->assertRedirect('/login');
        $this->post('/pembayaran/store', [])->assertRedirect('/login');
    }

    public function test_admin_can_view_pembayaran_index(): void
    {
        // Prepare data: Tahun, Kelas, Siswa, JenisPembayaran, Iuran, Pembayaran
        $ta = TahunAjaran::create(['nama' => '2025/2026', 'semester' => 'Ganjil', 'aktif' => true]);
        $kelas = Kelas::create(['nama' => 'X IPA', 'kapasitas' => 30, 'tahun_ajaran_id' => $ta->id]);
        $siswa = Siswa::create([
            'nis' => '111',
            'nama_depan' => 'A',
            'nama_belakang' => 'B',
            'kelas_id' => $kelas->id,
            'email' => 'a@b.com'
        ]);
        $jenis = JenisPembayaran::create([
            'kode' => 'SPP',
            'nama' => 'SPP',
            'nominal' => 100000,
            'frekuensi' => 'Bulanan'
        ]);
        $iuran = Iuran::create([
            'siswa_id' => $siswa->id,
            'jenis_pembayaran_id' => $jenis->id,
            'bulan' => 1,
            'status' => 'pending'
        ]);
        Pembayaran::create([
            'iuran_id' => $iuran->id,
            'order_id' => 'ORDER-' . $iuran->id,
            'jumlah' => $jenis->nominal,
            'metode' => 'manual',
            'status' => 'settlement'
        ]);

        $response = $this->actingAs($this->admin)->get('/pembayaran');
        $response->assertStatus(200)
            ->assertSee('ORDER-' . $iuran->id)
            ->assertSee('A B')
            ->assertSee('100.000');
    }

    public function test_admin_can_access_create_pembayaran_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/pembayaran/create');
        $response->assertStatus(200)
            ->assertSee('Bayar Iuran')
            ->assertSee('<select', false);
    }

    public function test_admin_can_store_pembayaran_and_update_iuran(): void
    {
        // Prepare Iuran
        $ta = TahunAjaran::create(['nama' => '2025/2026', 'semester' => 'Ganjil', 'aktif' => true]);
        $kelas = Kelas::create(['nama' => 'X IPA', 'kapasitas' => 30, 'tahun_ajaran_id' => $ta->id]);
        $siswa = Siswa::create([
            'nis' => '222',
            'nama_depan' => 'C',
            'nama_belakang' => 'D',
            'kelas_id' => $kelas->id,
            'email' => 'c@d.com'
        ]);
        $jenis = JenisPembayaran::create([
            'kode' => 'UANG',
            'nama' => 'Uang',
            'nominal' => 50000,
            'frekuensi' => 'Bulanan'
        ]);
        $iuran = Iuran::create([
            'siswa_id' => $siswa->id,
            'jenis_pembayaran_id' => $jenis->id,
            'bulan' => 2,
            'status' => 'pending'
        ]);

        // Post store
        $payload = [
            'order_id' => 'ORDER-' . $iuran->id . '-TST',
            'iuran_id' => $iuran->id,
            'jumlah' => 50000,
            'metode' => 'midtrans',
            'midtrans_id' => 'MID-' . $iuran->id,
            'status' => 'settlement',
        ];
        $this->actingAs($this->admin)
            ->post('/pembayaran/store', $payload)
            ->assertRedirect('/pembayaran');

        // Assert in database
        $this->assertDatabaseHas('pembayaran', [
            'order_id' => 'ORDER-' . $iuran->id . '-TST',
            'status' => 'settlement',
        ]);

        // Assert iuran status updated to 'lunas'
        $this->assertDatabaseHas('iuran', [
            'id' => $iuran->id,
            'status' => 'lunas',
        ]);
    }
}
