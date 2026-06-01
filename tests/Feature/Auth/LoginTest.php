<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_user_can_login(): void {
        User::factory()->create([
            'email' => 'user@example.com1',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@example.com1',
            'password' => 'password',
        ]); 

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'access_token'
        ]);

        $token = $response->json('access_token');

        $protectedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/protected-route');

        $protectedResponse->assertStatus(200);
    }
}
