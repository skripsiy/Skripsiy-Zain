<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_users()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
            'email' => 'agent.xyz@xena.com',
            'name' => 'Agent XYZ',
            'username' => 'agentxyz',
        ]);

        // 1. GET /admin/users (F-06)
        $response = $this->actingAs($admin)
            ->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Agent XYZ');

        // 2. POST /admin/users (F-07)
        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'New Agent',
                'email' => 'new.agent@xena.com',
                'username' => 'newagent',
                'role' => 'agent',
                'campaign' => 'besfixed',
                'status' => 'active',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User created successfully.');
        $this->assertDatabaseHas('users', [
            'email' => 'new.agent@xena.com',
            'username' => 'newagent',
        ]);

        // 3. PATCH /admin/users/{user}/role (F-08)
        $response = $this->actingAs($admin)
            ->patch(route('admin.users.updateRole', $agent), [
                'role' => 'team_leader',
                'status' => 'active', // status is required in updateRole
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User profile and status updated successfully.');
        $this->assertEquals('team_leader', $agent->fresh()->role);

        // 4. PATCH /admin/users/{user}/status (F-08)
        $response = $this->actingAs($admin)
            ->patch(route('admin.users.updateStatus', $agent), [
                'status' => 'inactive',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User status updated successfully.');
        $this->assertEquals('inactive', $agent->fresh()->status);

        // 5. DELETE /admin/users/{user} (F-09)
        $response = $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $agent));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User deleted successfully.');
        $this->assertDatabaseMissing('users', ['id' => $agent->id]);
    }

    public function test_non_admin_cannot_manage_users()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        $response = $this->actingAs($agent)
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }
}
