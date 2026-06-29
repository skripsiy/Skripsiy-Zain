<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AgentWorkSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentWorkSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_can_manage_work_sessions()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        // 1. GET /agent/work-session/status (offline initially)
        $response = $this->actingAs($agent)
            ->get(route('agent.work-session.status'));

        $response->assertOk();
        $response->assertJson([
            'status' => 'offline',
            'total_online_seconds' => 0,
        ]);

        // 2. POST /agent/work-session/toggle-online (offline -> online)
        $response = $this->actingAs($agent)
            ->post(route('agent.work-session.toggle-online'));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status' => 'online',
        ]);

        // 3. POST /agent/work-session/start-aux (online -> aux)
        $response = $this->actingAs($agent)
            ->post(route('agent.work-session.start-aux'));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status' => 'aux',
        ]);

        // 4. POST /agent/work-session/end-aux (aux -> online)
        $response = $this->actingAs($agent)
            ->post(route('agent.work-session.end-aux'));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status' => 'online',
        ]);

        // 5. POST /agent/work-session/end-shift (online -> offline)
        $response = $this->actingAs($agent)
            ->post(route('agent.work-session.end-shift'));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status' => 'offline',
        ]);
    }
}
