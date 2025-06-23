<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SiswaStatusTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Kelas $kelas1;
    protected Kelas $kelas2;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'admin']);
        $perm = Permission::create(['name' => 'edit siswa']);
        $role->givePermissionTo($perm);

        $this->withoutMiddleware([
            RoleMiddleware::class,
            PermissionMiddleware::class,
        ]);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $ta = TahunAjaran::create([
            'nama' => '2025/2026',
            'semester' => 'Ganjil',
            'aktif' => true,
        ]);

        $this->kelas1 = Kelas::create([
            'nama' => 'X IPA',
            'kapasitas' => 30,
            'tahun_ajaran_id' => $ta->id,
        ]);

        $this->kelas2 = Kelas::create([
            'nama' => 'XI IPA',
            'kapasitas' => 30,
            'tahun_ajaran_id' => $ta->id,
        ]);
    }

    public function test_admin_can_promote_transfer_and_graduate_siswa(): void
    {
        $siswa = Siswa::create([
            'nis' => '111',
            'nama_depan' => 'Test',
            'nama_belakang' => 'Student',
            'email' => 'test@example.com',
            'kelas_id' => $this->kelas1->id,
        ]);

        // naik kelas
        $this->actingAs($this->admin)
            ->post("/siswa/{$siswa->id}/naik-kelas", [
                'kelas_id' => $this->kelas2->id,
            ])
            ->assertRedirect();
        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id,
            'kelas_id' => $this->kelas2->id,
        ]);

        // pindah sekolah
        $this->actingAs($this->admin)
            ->post("/siswa/{$siswa->id}/pindah")
            ->assertRedirect();
        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id,
            'status_siswa' => 'nonaktif',
            'status_akhir_siswa' => 'pindah',
        ]);

        // lulus
        $this->actingAs($this->admin)
            ->post("/siswa/{$siswa->id}/lulus")
            ->assertRedirect();
        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id,
            'status_siswa' => 'lulus',
            'status_akhir_siswa' => 'lulus',
        ]);
    }
}
