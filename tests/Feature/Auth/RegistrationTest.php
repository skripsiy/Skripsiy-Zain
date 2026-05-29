<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * Registration is disabled - users are created by admin.
     * This test verifies the route returns 404.
     */
    public function test_registration_screen_can_be_rendered(): void
    {
        // Registration is disabled - returns 404
        $response = $this->get('/register');
        $response->assertStatus(404);
    }

    /**
     * Registration is disabled.
     */
    public function test_new_users_can_register(): void
    {
        // Registration is disabled - returns 404
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(404);
    }
}
