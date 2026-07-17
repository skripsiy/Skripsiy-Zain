<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BroadcastingAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Configure 'pusher' broadcaster so channel authorization callbacks are executed
        config([
            'broadcasting.default' => 'pusher',
            'broadcasting.connections.pusher.app_id' => '123456',
            'broadcasting.connections.pusher.key' => 'local-key',
            'broadcasting.connections.pusher.secret' => 'local-secret',
        ]);

        // Manually load routes/channels.php to bind the callbacks to the new broadcaster
        require base_path('routes/channels.php');
    }

    /**
     * Test 1: Team Leader berhasil mengotorisasi channel private-team-leader.
     */
    public function test_team_leader_can_authorize_team_leader_channel()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
            'status' => 'active',
        ]);

        $response = $this->actingAs($teamLeader)
            ->postJson('/broadcasting/auth', [
                'channel_name' => 'private-team-leader',
                'socket_id' => '1234.5678',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['auth']);
    }

    /**
     * Test 2: Agent tidak berhasil mengotorisasi channel private-team-leader (403).
     */
    public function test_agent_cannot_authorize_team_leader_channel()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        $response = $this->actingAs($agent)
            ->postJson('/broadcasting/auth', [
                'channel_name' => 'private-team-leader',
                'socket_id' => '1234.5678',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test 3: Pengguna tamu (guest) tidak bisa mengotorisasi channel private-team-leader (403).
     */
    public function test_guest_cannot_authorize_team_leader_channel()
    {
        $response = $this->postJson('/broadcasting/auth', [
            'channel_name' => 'private-team-leader',
            'socket_id' => '1234.5678',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test 4: Agent berhasil mengotorisasi channel private-agent.{id} miliknya sendiri.
     */
    public function test_agent_can_authorize_own_agent_channel()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        $response = $this->actingAs($agent)
            ->postJson('/broadcasting/auth', [
                'channel_name' => "private-agent.{$agent->id}",
                'socket_id' => '1234.5678',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['auth']);
    }

    /**
     * Test 5: Agent tidak bisa mengotorisasi channel private-agent.{id} milik agent lain.
     */
    public function test_agent_cannot_authorize_other_agent_channel()
    {
        $agent1 = User::factory()->create(['role' => 'agent', 'status' => 'active']);
        $agent2 = User::factory()->create(['role' => 'agent', 'status' => 'active']);

        $response = $this->actingAs($agent1)
            ->postJson('/broadcasting/auth', [
                'channel_name' => "private-agent.{$agent2->id}",
                'socket_id' => '1234.5678',
            ]);

        $response->assertStatus(403);
    }
}
