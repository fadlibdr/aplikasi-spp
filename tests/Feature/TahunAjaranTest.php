<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TahunAjaranTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_is_redirected_to_login_when_accessing_tahun_ajaran_index()
    {
        $response = $this->get('/tahun-ajaran');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
