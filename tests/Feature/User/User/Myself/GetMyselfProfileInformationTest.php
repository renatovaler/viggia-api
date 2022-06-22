<?php

namespace Tests\Feature\User\User\Myself;

use App\Domain\User\Models\User;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetMyselfProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_myself_profile_information_with_not_authenticated_user()
    {
        $response = $this->getJson('/users/myself/profile');
        $response->assertUnauthorized();
    }

    public function test_get_myself_profile_with_an_authenticated_user()
    {
        $user = User::factory()->create();
        $user->password_changed_at = now();
        $user->save();

        $response = $this->actingAs($user)
        ->getJson('/users/myself/profile');
        $response->assertOk();
    }
}
