<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Tests\TestCase;

class EventCrudTest extends TestCase
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

    public function test_admin_can_crud_event(): void
    {
        $response = $this->actingAs($this->admin)->get('/events');
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get('/events/create');
        $response->assertStatus(200);

        $data = [
            'title' => 'MPLS',
            'start_date' => '2025-07-10',
            'end_date' => '2025-07-12',
        ];
        $this->actingAs($this->admin)
            ->post('/events', $data)
            ->assertRedirect('/events');
        $this->assertDatabaseHas('events', ['title' => 'MPLS']);

        $event = Event::where('title', 'MPLS')->first();

        $response = $this->actingAs($this->admin)->get("/events/{$event->id}/edit");
        $response->assertStatus(200);

        $update = array_merge($data, ['title' => 'Updated MPLS']);
        $this->actingAs($this->admin)
            ->put("/events/{$event->id}", $update)
            ->assertRedirect('/events');
        $this->assertDatabaseHas('events', ['title' => 'Updated MPLS']);

        $this->actingAs($this->admin)
            ->delete("/events/{$event->id}")
            ->assertRedirect('/events');
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}
