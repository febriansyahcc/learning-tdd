<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
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

    public function test_user_can_register(): void {
        $response = $this->postJson('/api/auth/register',[
            'name' => 'Budi',
            'email' => 'budii@gmail.com', 
            'password' => '12345678'
        ]); 

        $response->assertStatus(201);

        $this->assertDatabaseHas('users',[ 
            'email' => 'budii@gmail.com'
        ]);

    }

    
    public function test_name_is_required(): void {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
        ]);
            
        $response->assertStatus(422);
        
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_email_is_required(): void{
        $response = $this->postJson('/api/auth/register',[
            'name' => 'Budi',
            'email' => '', 
            'password' => '12345678'
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['email']);
    }

    public function test_email_is_valid(): void {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'budi',
            'email' => 'budi',
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['email']);
    }

    public function test_email_is_unique(): void {
        User::factory()->create([
            'email' => 'budii@gmail.com'
        ]);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'xx',
            'email' => 'budii@gmail.com'
        ]);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['email']);
    }

    public function test_password_is_required(): void {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Budi',
            'email' => 'budii@gmail.com',
            'password' => ''
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['password']);
    }

    public function test_password_must_be_at_least_8_characters() : void {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Budi',
            'email' => 'budii@gmail.com',
            'password' => '1234567'
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['password']);
    }
}
