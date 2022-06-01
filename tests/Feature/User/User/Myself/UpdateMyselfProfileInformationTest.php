<?php

namespace Tests\Feature\User\User\Myself;

use App\Domain\User\Models\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateMyselfProfileInformationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_update_myself_profile_information_with_not_authenticated_user()
    {
        $now = now();
        $response = $this->putJson('/myself/profile', [
            'id' => $this->faker->randomDigitNotNull(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail()
        ]);
        $response->assertUnauthorized();
    }

    public function test_update_myself_profile_information_with_an_authenticated_user()
    {
        $user = User::factory()->create();
        $now = now();
        $response = $this->actingAs($user)
                    ->putJson('/myself/profile', [
                        'id' => $user->id,
                        'name' => $this->faker->name(),
                        'email' => $user->email
                    ]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at'
            ]
        ])
        ->assertOk();
    }
}
