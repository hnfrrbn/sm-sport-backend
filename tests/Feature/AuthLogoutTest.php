<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
