<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        $this->actingAs($customer);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
    }
}
