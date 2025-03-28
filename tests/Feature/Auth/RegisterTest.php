<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/user/register', [
            'username' => fake()->username,
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => fake()->email,
            'mobile' => '09123456789',
            'password' => 'password123',
            'role' => 'ADMIN',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'results' => [
                    'username',
                    'firstName',
                    'lastName',
                    'email',
                    'role',
                ],
                'code',
                'error'
            ]);
    }
}
