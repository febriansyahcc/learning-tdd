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

    public function test_email_is_required(): void {
        $response = $this->postJson('/api/auth/login' , [
            'email' => '',
        ]);

        $response->assertStatus(422);
    }


    public function test_password_is_required(): void {
        $response = $this->postJson('/api/auth/login' , [
            'email' => 'user@example.com',
            'password' => '',
        ]);

        $response->assertStatus(422);
    }

    public function test_email_must_be_valid(): void {
        $response = $this->postJson('/api/auth/login' , [
            'email' => 'userexample.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_login_with_unregistered_email(): void {
        $response = $this->postJson('/api/auth/login' , [
            'email' => 'unregistered@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_cannot_login_with_wrong_password(): void {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/auth/login' , [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }
}
