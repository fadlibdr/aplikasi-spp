<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\JenisPembayaran;
use App\Models\Iuran;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class Modul6Test extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Nonaktifkan middleware Spatie agar tests fokus pada logic
        $this->withoutMiddleware([
            RoleMiddleware::class,
            PermissionMiddleware::class,
        ]);

        // Buat role 'admin' dan permissions untuk modul 6
        $role = Role::create(['name' => 'admin']);
        $perms = [
            'view jenis_pembayaran',
            'create jenis_pembayaran',
            'edit jenis_pembayaran',
            'delete jenis_pembayaran',
            'view iuran',
            'create iuran',
            'edit iuran',
            'delete iuran',
        ];
        foreach ($perms as $p) {
            Permission::create(['name' => $p]);
        }
        $role->givePermissionTo($perms);

        // Buat user admin dan assign role
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Login');
    }

    public function test_guest_is_redirected_from_jenis_and_iuran(): void
    {
        $this->get('/jenis-pembayaran')->assertRedirect('/login');
        $this->get('/jenis-pembayaran/create')->assertRedirect('/login');
        $this->post('/jenis-pembayaran', [])->assertRedirect('/login');

        $this->get('/iuran')->assertRedirect('/login');
        $this->get('/iuran/create')->assertRedirect('/login');
        $this->post('/iuran', [])->assertRedirect('/login');
    }

    public function test_admin_can_crud_jenis_pembayaran(): void
    {
        // VIEW INDEX
        $response = $this->actingAs($this->admin)->get('/jenis-pembayaran');
        $response->assertStatus(200);

        // CREATE PAGE
        $response = $this->actingAs($this->admin)->get('/jenis-pembayaran/create');
        $response->assertStatus(200)->assertSee('Tambah');

        // STORE
        $data = [
            'kode' => 'TEST',
            'nama' => 'Test Pembayaran',
            'nominal' => 100000,
            'frekuensi' => 'Bulanan',
        ];
        $this->actingAs($this->admin)
            ->post('/jenis-pembayaran', $data)
            ->assertRedirect('/jenis-pembayaran');
        $this->assertDatabaseHas('jenis_pembayaran', ['kode' => 'TEST']);

        $jenis = JenisPembayaran::where('kode', 'TEST')->first();

        // EDIT PAGE
        $response = $this->actingAs($this->admin)
            ->get("/jenis-pembayaran/{$jenis->id}/edit");
        $response->assertStatus(200)->assertSee('Test Pembayaran');

        // UPDATE
        $update = array_merge($data, ['nama' => 'Updated Pembayaran']);
        $this->actingAs($this->admin)
            ->put("/jenis-pembayaran/{$jenis->id}", $update)
            ->assertRedirect('/jenis-pembayaran');
        $this->assertDatabaseHas('jenis_pembayaran', ['nama' => 'Updated Pembayaran']);

        // DELETE
        $this->actingAs($this->admin)
            ->delete("/jenis-pembayaran/{$jenis->id}")
            ->assertRedirect();
        $this->assertDatabaseMissing('jenis_pembayaran', ['id' => $jenis->id]);
    }

    public function test_admin_can_crud_iuran(): void
    {
        // Siapkan Tahun Ajaran & Kelas
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

        // VIEW INDEX
        $response = $this->actingAs($this->admin)->get('/iuran');
        $response->assertStatus(200);

        // CREATE PAGE
        $response = $this->actingAs($this->admin)->get('/iuran/create');
        $response->assertStatus(200)->assertSee('Tambah');

        // STORE
        $jenis = JenisPembayaran::create([
            'kode' => 'SPP',
            'nama' => 'SPP Bulanan',
            'nominal' => 150000,
            'frekuensi' => 'Bulanan',
        ]);
        $siswa = User::factory()->create(); // as dummy siswa
        $iuranData = [
            'siswa_id' => $siswa->id,
            'jenis_pembayaran_id' => $jenis->id,
            'bulan' => 5,
        ];
        $this->actingAs($this->admin)
            ->post('/iuran', $iuranData)
            ->assertRedirect('/iuran');
        $this->assertDatabaseHas('iuran', ['bulan' => 5]);

        $iuran = Iuran::where('bulan', 5)->first();

        // EDIT PAGE
        $response = $this->actingAs($this->admin)
            ->get("/iuran/{$iuran->id}/edit");
        $response->assertStatus(200)->assertSee('Edit');

        // UPDATE
        $updateData = array_merge($iuranData, ['bulan' => 6]);
        $this->actingAs($this->admin)
            ->put("/iuran/{$iuran->id}", $updateData)
            ->assertRedirect('/iuran');
        $this->assertDatabaseHas('iuran', ['bulan' => 6]);

        // DELETE
        $this->actingAs($this->admin)
            ->delete("/iuran/{$iuran->id}")
            ->assertRedirect();
        $this->assertDatabaseMissing('iuran', ['id' => $iuran->id]);
    }
}
