<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;

class CekPembayaranTest extends TestCase
{
    use RefreshDatabase;

    protected User $siswaUser;
    protected Siswa $siswa1;
    protected Siswa $siswa2;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable Spatie middleware
        $this->withoutMiddleware([
            RoleMiddleware::class,
            PermissionMiddleware::class,
        ]);

        Role::create(['name' => 'siswa']);

        $ta = TahunAjaran::create(['nama' => '2025/2026', 'semester' => 'Ganjil', 'aktif' => true]);
        $kelas = Kelas::create(['nama' => 'X', 'kapasitas' => 30, 'tahun_ajaran_id' => $ta->id]);

        $this->siswa1 = Siswa::create([
            'nis' => '001',
            'nisn' => '111',
            'nama_depan' => 'Siswa',
            'nama_belakang' => 'Satu',
            'email' => 'siswa1@example.com',
            'kelas_id' => $kelas->id,
        ]);

        $this->siswa2 = Siswa::create([
            'nis' => '002',
            'nisn' => '222',
            'nama_depan' => 'Siswa',
            'nama_belakang' => 'Dua',
            'email' => 'siswa2@example.com',
            'kelas_id' => $kelas->id,
        ]);

        $this->siswaUser = User::factory()->create(['email' => $this->siswa1->email]);
        $this->siswaUser->assignRole('siswa');
    }

    public function test_student_cannot_check_other_student_data(): void
    {
        $response = $this->actingAs($this->siswaUser)
            ->post('/cek-pembayaran', ['nisn' => $this->siswa2->nisn]);

        $response->assertSessionHasErrors();
    }
}
