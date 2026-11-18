<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDeleteFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_user_and_redirect_to_admin_user_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $userToDelete = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $userToDelete));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }
}
