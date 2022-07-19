<?php

namespace Tests\Vehicle;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteVehicleLocalizationTest extends TestCase
{
    use RefreshDatabase;
	
    public function test_delete_vehicle_localization_information_with_not_authenticated_user()
    {
        // Cria o ponto de localização
		$localization = $this->createVehicleLocalization();
        
		// Faz a requisição para deletar o registro
		$response = $this->deleteJson('/vehicle/localizations/'.$localization->id);
		
		// Verifica se o usuário não está logado
        $this->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function test_delete_vehicle_localization_information_with_admin_user()
    {
        // Cria um usuário admin e super_admin
        $user = $this->createAdminUser();

        // Faz login
        Auth::loginUsingId($user->id);
		
        // Cria o ponto de localização
		$localization = $this->createVehicleLocalization();
        
		// Faz a requisição para deletar o registro
		$response = $this->actingAs($user)->deleteJson('/vehicle/localizations/'.$localization->id);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();
        
		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();
    }
}
