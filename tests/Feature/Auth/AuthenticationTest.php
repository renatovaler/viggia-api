<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = $this->createCommonUser();

        $response = $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertNoContent();
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = $this->createCommonUser();

        $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
