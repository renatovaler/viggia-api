<?php

namespace Tests\Feature\Vehicle;

use App\Domain\User\Models\User;
use App\Domain\Vehicle\Models\VehicleLocalization;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateVehicleLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_vehicle_localization_with_not_authenticated_user()
    {
		// Faz a requisição para criar o registro sem usuário logado
		$response = $this->postCreateVehicleLocalization( self::NOT_AUTHENTICATED );

		// Verifica se o usuário não está logado
        $this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }
/*
    public function test_create_vehicle_localization_with_common_user()
    {
		// Faz a requisição para criar o registro com usuário comum
		$response = $this->postCreateVehicleLocalization(true);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function test_create_vehicle_localization_with_admin_user()
    {
		// Faz a requisição para criar o registro com usuário admin/super_admin
		$response = $this->postCreateVehicleLocalization(true, true);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta
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
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }
*/
	/*
	* Create Vehicle Localization for tests
	* @return result of post http request
	*/
	public function postCreateVehicleLocalization(bool $authenticated = false, bool $commonUser = true)
	{
		$localizationPoints = $this->getLocalizationPoints();

		$data = [
            'license_plate' => Str::random(7),
			'localization_latitude' => $localizationPoints[0],
			'localization_longitude' => $localizationPoints[1],
			'localized_at' => now()->format('Y-m-d H:i:s'),
		];

		if(true === $authenticated) {
			$user = (true === $commonUser ? $this->createCommonUser() : $this->createAdminUser());

            // Faz login
            Auth::loginUsingId($user->id);

			$data['user_id'] = $user->id;

			return $this->actingAs($user)->post('/vehicle/localizations', $data);
		}

		return $this->post('/vehicle/localizations', $data);
	}
}
