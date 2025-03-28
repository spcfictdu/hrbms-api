<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_login_with_valid_credentials(): void
    {
        // Create a user with known credentials
        \App\Models\User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('developer'),
        ]);

        $response = $this->postJson(
            '/api/user/admin/login',
            [
                'username' => 'admin',
                'password' => 'developer'
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure(['results' => ['token']]);
    }

    public function test_user_login_with_wrong_credentials(): void
    {
        $response = $this->postJson(
            '/api/user/admin/login',
            [
                'username' => 'admin',
                'password' => 'wrongpassword'
            ]
        );

        $response->assertStatus(401);
    }

    public function test_user_login_with_missing_credentials(): void
    {
        $response = $this->postJson('/api/user/admin/login', [
            'username' => 'admin',
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


    public function test_user_login_with_non_existent_user(): void
    {
        $response = $this->postJson(
            '/api/user/admin/login',
            [
                'username' => 'nonexistentuser',
                'password' => 'password'
            ]
        );

        $response->assertStatus(401); // Unauthorized
    }

    public function test_user_login_with_invalid_username_format(): void
    {
        $response = $this->postJson(
            '/api/user/admin/login',
            [
                'username' => 'invalid username!', // Invalid format
                'password' => 'password'
            ]
        );

        $response->assertStatus(422) // Unprocessable Entity
            ->assertJsonStructure([
                'message', // Ensure the response has a "message" key
                'results' => ['username'], // The correct location of validation errors
                'code',
                'errors',
            ]);
    }

    public function test_user_login_rate_limiting(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->postJson(
                '/api/user/admin/login',
                [
                    'username' => 'admin',
                    'password' => 'wrongpassword'
                ]
            );
        }

        $response = $this->postJson(
            '/api/user/admin/login',
            [
                'username' => 'admin',
                'password' => 'wrongpassword'
            ]
        );

        $response->assertStatus(429); // Too Many Requests
    }

    // Not implemented yet
    // public function test_user_login_with_valid_credentials_and_token_expiry(): void
    // {
    //     $user = \App\Models\User::factory()->create([
    //         'username' => 'admin',
    //         'password' => bcrypt('developer'),
    //     ]);

    //     $response = $this->postJson(
    //         '/api/user/admin/login',
    //         [
    //             'username' => 'admin',
    //             'password' => 'developer'
    //         ]
    //     );

    //     $response->assertStatus(200)
    //         ->assertJsonStructure(['results' => ['token']]);

    //     // Simulate token expiry (if applicable)
    //     $this->travel(2)->hours(); // Assuming token expires in 1 hour

    //     $token = $response->json('results.token');
    //     $authResponse = $this->withHeaders(['Authorization' => "Bearer $token"])
    //         ->getJson('/api/room-status'); // Replace with an actual protected route

    //     $authResponse->assertStatus(401); // Unauthorized
    // }
}
