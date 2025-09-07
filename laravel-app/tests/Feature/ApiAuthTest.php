<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_login_flow(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'secret123',
        ]);
        $response->assertStatus(200)->assertJson(['success' => true]);

        $login = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'secret123',
        ]);
        $login->assertStatus(200)->assertJsonStructure(['data' => ['token']]);
    }
}
