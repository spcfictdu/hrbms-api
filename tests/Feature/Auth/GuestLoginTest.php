<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuestLoginTest extends TestCase
{
    public function test_guest_can_login_with_valid_credentials(): void
    {
        // Create a user with known credentials
        // \App\Models\User::factory()->create([
        //     'email' => 'guest@gmail.com',
        //     'password' => bcrypt('guest'),
        // ]);

        $response = $this->postJson(
            '/api/user/guest/login',
            [
                'email' => 'guest@gmail.com',
                'password' => 'guest'
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure(['results' => ['token']]);
    }

    public function test_guest_login_with_wrong_credentials(): void
    {
        $response = $this->postJson(
            '/api/user/guest/login',
            [
                'email' => 'dev@gmail.com',
                'password' => 'guests'
            ]
        );

        $response->assertStatus(401);
    }

    public function test_guest_login_with_missing_credentials(): void
    {
        $response = $this->postJson('/api/user/guest/login', [
            'email' => 'dev@gmail.com',
            // Missing password
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message', // Ensure the response has a "message" key
                'results' => ['password'], // The correct location of validation errors
                'code',
                'errors',
            ]);
    }

    public function test_guest_login_with_non_existent_guest(): void
    {
        $response = $this->postJson(
            '/api/user/guest/login',
            [
                'email' => 'wow@gmail.com',
                'password' => 'password'
            ]
        );

        $response->assertStatus(401); // Unauthorized
    }

    public function test_guest_login_with_invalid_email_format(): void
    {
        $response = $this->postJson(
            '/api/user/guest/login',
            [
                'email' => 'test',
                'password' => 'password'
            ]
        );

        $response->assertStatus(422) // Unprocessable Entity
            ->assertJsonStructure([
                'message', // Ensure the response has a "message" key
                'results' => ['email'], // The correct location of validation errors
                'code',
                'errors',
            ]);
    }

    public function test_guest_login_rate_limiting(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->postJson(
                '/api/user/guest/login',
                [
                    'email' => 'dev@gmail.com',
                    'password' => 'guests'
                ]
            );
        }

        $response = $this->postJson(
            '/api/user/guest/login',
            [
                'email' => 'dev@gmail.com',
                'password' => 'guests'
            ]
        );

        $response->assertStatus(429); // Too Many Requests
    }
}
