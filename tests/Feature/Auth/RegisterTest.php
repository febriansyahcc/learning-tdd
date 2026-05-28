<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_register(): void {
        $response = $this->postJson('/api/auth/register',[
            'name' => 'Budi',
            'email' => 'budii@gmail.com', 
            'password' => '12345678'
        ]); 

        $response->assertStatus(201);

        $this->assertDatabaseHas('users',[ 
            'email' => 'test@gmail.com'
        ]);

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
}
