<?php

namespace Tests\Feature\Vehicle;

use App\Domain\User\Models\User;
use App\Domain\Vehicle\Models\VehicleLocalization;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetVehicleLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_vehicle_localization_information_with_not_authenticated_user()
    {
        $localization = VehicleLocalization::factory()->create();
        $response = $this->getJson('/vehicle/localizations/'.$localization->id);
        $response->assertUnauthorized();
    }

    public function test_get_vehicle_localization_information_with_an_authenticated_user()
    {
        $user = User::factory()->create();
        $user->password_changed_at = now();
        $user->save();
		
        $localization = VehicleLocalization::factory()->create();

        $response = $this->actingAs($user)->getJson('/vehicle/localizations/'.$localization->id);
        $response->assertAuthenticated();
        $response->assertJsonStructure([
			'data' => [
				[
					'id',
					'license_plate',
					'localization_latitude',
					'localization_longitude',
					'localized_at'
				]
			]
		])
		->assertOk();
    }
}
