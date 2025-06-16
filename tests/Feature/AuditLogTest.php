<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class AuditLogTest extends TestCase
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

    public function test_request_is_logged(): void
    {
        $this->actingAs($this->admin)->get('/dashboard');

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'method' => 'GET',
            'url' => url('/dashboard'),
        ]);
    }

    public function test_admin_can_view_logs(): void
    {
        AuditLog::create([
            'user_id' => $this->admin->id,
            'method' => 'GET',
            'url' => url('/test'),
            'ip_address' => '127.0.0.1',
            'user_agent' => 'testing',
        ]);

        $response = $this->actingAs($this->admin)->get('/audit-logs');
        $response->assertStatus(200)->assertSee('Audit Logs');
    }
}

