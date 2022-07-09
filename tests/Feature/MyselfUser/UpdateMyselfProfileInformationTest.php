<?php

namespace Tests\Feature\MyselfUser;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateMyselfProfileInformationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_update_myself_user_profile_with_not_authenticated_user()
    {
		// Faz a requisição para atualizar o registro sem informar usuário logado
		$response = $this->putUpdateUser( self::NOT_AUTHENTICATED );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function test_update_myself_user_profile_with_authenticated_user()
    {
		// Faz a requisição para atualizar o registro com usuário logado
		$response = $this->putUpdateUser( self::AUTHENTICATED );

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

	/*
	* Create Vehicle Localization for tests
	* @return result of post http request
	*/
	public function putUpdateUser(bool $authenticated = false)
	{

		// Cria um usuário qualquer
		$user = $this->createCommonUser();

		// Monta o array com os dados de atualização
		$data = [
			'id' => $user->id,
			'name' => $this->faker->name(),
			'email' => $user->email
		];

		// Verifica se a requisição deve ser com usuário logado ou não
		if(true === $authenticated) {

			// Retorna a resposta da requisição feita com usuário logado
			return $this->actingAs($user)->putJson('/myself/profile', $data);

		} else {

			// Retorna a resposta da requisição feita sem usuário logado
			return $this->putJson('/myself/profile', $data);

		}
	}
}
