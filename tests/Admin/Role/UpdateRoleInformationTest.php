<?php

namespace Tests\Admin\Role;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateRoleInformationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function _test_update_role_information_with_not_authenticated_user()
    {
		// Faz a requisição para atualizar o registro sem informar usuário logado
		$response = $this->putUpdateUser( self::NOT_AUTHENTICATED );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function _test_update_user_profile_with_common_user()
    {
		// Faz a requisição para atualizar o registro com usuário comum
		$response = $this->putUpdateUser( self::AUTHENTICATED, self::COMMON_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function _test_update_user_profile_with_admin_user()
    {
		// Faz a requisição para atualizar o registro com usuário admin/super_admin
		$response = $this->putUpdateUser( self::AUTHENTICATED, self::ADMIN_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

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

	/*
	* Update role information for tests
	* @return result of post http request
	*/
	public function putUpdateUser(bool $authenticated = false, bool $commonUser = true)
	{
        // Cria um usuário qualquer
		$targetUser = $this->createCommonUser();

		// Monta o array com os dados de atualização
		$data = [
			'id' => $targetUser->id,
			'name' => $this->faker->name(),
			'email' => $targetUser->email
		];

		// Verifica se a requisição deve ser com usuário logado ou não
		if(true === $authenticated) {

			// Verifica se o usuário deve ser comum ou admin/super admin
			$loggedUser = (true === $commonUser ? $this->createCommonUser() : $this->createAdminUser());

			// Retorna a resposta da requisição feita com usuário logado
			return $this->actingAs($loggedUser)->putJson('/admin/users/' .$targetUser->id. '/profile', $data);
		}
		// Retorna a resposta da requisição feita sem usuário logado
		return $this->putJson('/admin/users/' .$targetUser->id. '/profile', $data);
	}
}
