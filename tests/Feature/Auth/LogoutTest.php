<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_logout(): void
    {
        // $user = \App\Models\User::factory()->create([
        //     'username' => 'admin',
        //     'password' => bcrypt('developer'),
        // ]);

        $loginResponse = $this->postJson(
            '/api/user/admin/login',
            [
                'username' => 'admin',
                'password' => 'developer'
            ]
        );

        $token = $loginResponse->json('results')['token'];

        // Log out
        $logoutResponse = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->getJson('/api/user/logout');

        $logoutResponse->assertStatus(200);

        // // Ensure token is invalidated
        // $authResponse = $this->withHeaders(['Authorization' => "Bearer $token"])
        //     ->getJson('/api/room-status'); // Replace with an actual protected route

        // $authResponse->assertStatus(404); // Unauthorized
    }
}
