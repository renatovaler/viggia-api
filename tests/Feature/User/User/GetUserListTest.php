<?php

namespace Tests\Feature\User\User\Myself;

use App\Domain\User\Models\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserListTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_user_list_with_not_authenticated_user()
    {
        $user = User::factory()->count(10)->create();

        $response = $this->getJson('/users/list');
        $response->assertUnauthorized();
    }

    public function test_get_user_list_with_an_authenticated_user()
    {
        $users = User::factory()->count(10)->create();

        $user = User::factory()->create();
        $user->password_changed_at = now();
        $user->save();

        $response = $this->actingAs($user)
                    ->getJson('/users/list');
                    $response->assertJsonStructure([
                        'data' => [
                            [
                                'id',
                                'name',
                                'email',
                                'email_verified_at'
                            ]
                        ]
                    ])
                    ->assertOk();
    }
}
