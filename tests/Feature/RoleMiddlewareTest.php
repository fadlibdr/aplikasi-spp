<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed a dummy permission so assignRole() won't fail
        Permission::create(['name' => 'dummy']);

        // Register the 'role' middleware alias (Spatie)
        $this->app['router']->aliasMiddleware(
            'role',
            \Spatie\Permission\Middleware\RoleMiddleware::class
        );

        // Define a test route protected by role:admin
        Route::middleware(['web', 'auth', 'role:admin'])
            ->get('/role-test', fn() => 'OK')
            ->name('role.test');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/role-test')
            ->assertRedirect('/login');
    }

    public function test_user_without_admin_role_sees_403(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get('/role-test')
            ->assertStatus(403);
    }

    public function test_user_with_admin_role_can_access(): void
    {
        // Create and assign 'admin' role
        $role = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)
            ->get('/role-test')
            ->assertStatus(200)
            ->assertSee('OK');
    }
}
