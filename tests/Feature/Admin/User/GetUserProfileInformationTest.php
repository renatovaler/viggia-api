<?php

namespace Tests\Feature\Admin\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_user_profile_with_not_authenticated_user()
    {
        // Cria o usuário
		$targetUser = $this->createUser();
		
		// Faz a requisição para obter os dados dos registros sem informar usuário logado
        $response = $this->getJson('/admin/users/'. $targetUser->id);
		
		// Verifica se o usuário não está logado
        $response->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function test_get_user_profile_with_common_user()
    {
        // Cria um usuário comum (não admin)
        $loggedUser = $this->createCommonUser();
		
        // Cria o usuário a ser pesquisado
		$targetUser = $this->createUser();
		
		// Faz a requisição para obter os dados dos registros informando um usuário comum logado
        $response = $this->actingAs($loggedUser)->getJson('/admin/users/'. $targetUser->id);
        
		// Verifica se o usuário está logado
		$response->assertAuthenticated();
		
		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
				[
					'id',
					'name',
					'email',
					'email_verified_at'
				]
			]
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }

    public function test_get_user_profile_with_admin_user()
    {
        // Cria um usuário admin e super_admin
        $loggedUser = $this->createAdminUser();
		
        // Cria o usuário a ser pesquisado
		$targetUser = $this->createUser();
		
		// Faz a requisição para obter os dados dos registros informando um usuário admin/super_admin logado
        $response = $this->actingAs($loggedUser)->getJson('/admin/users/'. $targetUser->id);
        
		// Verifica se o usuário está logado
		$response->assertAuthenticated();
		
		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
				[
					'id',
					'name',
					'email',
					'email_verified_at'
				]
			]
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }
}
