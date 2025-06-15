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

    public function test_dashboard_displays_chart_and_status_table(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200)
                 ->assertSee('id="paymentChart"', false)
                 ->assertSee('id="studentStatusTable"', false);
    }
}
