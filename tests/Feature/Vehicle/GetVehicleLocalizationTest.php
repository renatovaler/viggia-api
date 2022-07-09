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
        // Cria um novo ponto de localização
		$localization = $this->createVehicleLocalization();
		
		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->getJson('/vehicle/localizations/'.$localization->id);
		
		// Verifica se o usuário não está logado
        $response->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function test_get_vehicle_localization_information_with_common_user()
    {
        // Cria um usuário comum (não admin)
        $user = $this->createCommonUser();
		
        // Cria um novo ponto de localização
		$localization = $this->createVehicleLocalization();
		
		// Faz a requisição para obter os dados do registro informando um usuário comum logado
        $response = $this->actingAs($user)->getJson('/vehicle/localizations/'.$localization->id);
        
		// Verifica se o usuário está logado
		$response->assertAuthenticated();
		
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

    public function test_get_vehicle_localization_information_with_admin_user()
    {
        // Cria um usuário admin e super_admin
        $user = $this->createAdminUser();
		
        // Cria um novo ponto de localização
		$localization = $this->createVehicleLocalization();
		
		// Faz a requisição para obter os dados do registro informando um usuário admin/super_admin logado
        $response = $this->actingAs($user)->getJson('/vehicle/localizations/'.$localization->id);
        
		// Verifica se o usuário está logado
		$response->assertAuthenticated();
		
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
}
