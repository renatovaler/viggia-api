<?php

namespace Tests\Feature\Vehicle;

use App\Domain\User\Models\User;
use App\Domain\Vehicle\Models\VehicleLocalization;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetVehicleLocalizationListTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_vehicle_localization_list_with_not_authenticated_user()
    {
        VehicleLocalization::factory()->count(10)->create();
        $response = $this->getJson('/vehicle/localizations');
        $response->assertUnauthorized();
    }

    public function test_get_vehicle_localization_list_with_an_authenticated_user()
    {
        VehicleLocalization::factory()->count(10)->create();

        $user = User::factory()->create();
        $user->password_changed_at = now();
        $user->save();
		
        $response = $this->actingAs($user)->getJson('/vehicle/localizations');
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
