<?php

namespace Tests\Feature\Vehicle;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateVehicleLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_vehicle_localization_information_with_not_authenticated_user()
    {
		// Faz a requisição para atualizar o registro sem informar usuário logado
		$response = $this->putUpdateVehicleLocalization( self::NOT_AUTHENTICATED );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function test_update_vehicle_localization_information_with_common_user()
    {
		// Faz a requisição para atualizar o registro com usuário comum
		$response = $this->putUpdateVehicleLocalization( self::AUTHENTICATED, self::COMMON_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function test_update_vehicle_localization_information_with_admin_user()
    {
		// Faz a requisição para atualizar o registro com usuário comum
		$response = $this->putUpdateVehicleLocalization( self::AUTHENTICATED, self::ADMIN_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
                'id',
                'license_plate',
                'localization_latitude',
                'localization_longitude',
                'localized_at'
			]
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }

	/*
	* Create Vehicle Localization for tests
	* @return result of post http request
	*/
	public function putUpdateVehicleLocalization(bool $authenticated = false, bool $commonUser = true)
	{
        // Cria um ponto de localização na DB
		$localization = $this->createVehicleLocalization();

		// Pega os pontos de GPS
		$localizationPoints = $this->getLocalizationPoints();

		// Monta o array com os dados de atualização
		$data = [
            'id' => $localization->id,
            'license_plate' => Str::random(7),
			'localization_latitude' => $localizationPoints[0],
			'localization_longitude' => $localizationPoints[1],
			'localized_at' => now()->toDateTimeString()
		];

		// Verifica se a requisição deve ser com usuário logado ou não
		if(true === $authenticated) {

			// Verifica se o usuário deve ser comum ou admin/super admin
			$user = (true === $commonUser ? $this->createCommonUser() : $this->createAdminUser());
			// Especifica o ID do usuário responsável pela requisição
			$data['user_id'] = $user->id;
			// Retorna a resposta da requisição feita com usuário logado
			return $this->actingAs($user)->putJson('/vehicle/localizations/'.$localization->id, $data);
		}
		// Retorna a resposta da requisição feita sem usuário logado
		return $this->putJson('/vehicle/localizations/'.$localization->id, $data);
	}
}
