<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Kelas;
use App\Models\Siswa;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create dummy permission so assignRole() works
        Permission::create(['name' => 'dummy']);

        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'siswa']);
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')
            ->assertRedirect('/login');
    }

    public function test_non_admin_user_sees_403_on_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertStatus(403);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('Admin Dashboard');
    }

    public function test_guest_is_redirected_from_student_dashboard(): void
    {
        $this->get('/dashboard-siswa')
            ->assertRedirect('/login');
    }

    public function test_non_siswa_user_sees_403_on_student_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get('/dashboard-siswa')
            ->assertStatus(403);
    }

    public function test_siswa_can_access_student_dashboard(): void
    {
        $kelas = Kelas::create(['nama' => 'X IPA', 'kapasitas' => 30]);
        $user = User::factory()->create();
        $user->assignRole('siswa');

        Siswa::create([
            'nis' => '12345',
            'nama_depan' => 'John',
            'nama_belakang' => 'Doe',
            'kelas_id' => $kelas->id,
            'email' => $user->email,
        ]);

        $this->actingAs($user)
            ->get('/dashboard-siswa')
            ->assertStatus(200)
            ->assertSee('Dashboard Siswa');
    }
}
