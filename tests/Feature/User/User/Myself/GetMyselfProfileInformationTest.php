<?php

namespace Tests\Feature\User\User\Myself;

use App\Domain\User\Models\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetMyselfProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_myself_profile_information_with_not_authenticated_user()
    {
        $response = $this->getJson('/myself/profile');
        $response->assertUnauthorized();
    }

    public function test_get_myself_profile_with_an_authenticated_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/myself/profile');

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at'
            ]
        ])
        ->assertOk();
    }
}
