<?php

namespace Tests\Feature\MyselfUser;

use App\Domain\User\Models\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetMyselfProfileInformationTest extends TestCase
{
    use RefreshDatabase;

	// $response = $this->getJson('/myself/profile');

    public function test_get_myself_user_profile_with_not_authenticated_user()
    {
		// Faz a requisição para obter os dados dos registros sem informar usuário logado
        $response = $this->getJson('/myself/profile');

		// Verifica se o usuário não está logado
        $this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function test_get_myself_user_profile_with_authenticated_user()
    {
        // Cria um usuário comum (não admin)
        $loggedUser = $this->createCommonUser();

		// Faz a requisição para obter os dados dos registros informando um usuário logado
        $response = $this->actingAs($loggedUser)->getJson('/myself/profile');

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'system_roles'
			]
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }
}
