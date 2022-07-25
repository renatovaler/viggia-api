<?php

namespace Tests\Admin\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function _test_update_user_password_with_not_authenticated_user()
    {
		// Faz a requisição para atualizar o registro sem informar usuário logado
		$response = $this->putUpdateUser( self::NOT_AUTHENTICATED );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function _test_update_user_password_with_common_user()
    {
		// Faz a requisição para atualizar o registro com usuário comum
		$response = $this->putUpdateUser( self::AUTHENTICATED, self::COMMON_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function _test_update_user_password_with_admin_user()
    {
		// Faz a requisição para atualizar o registro com usuário comum
		$response = $this->putUpdateUser( self::AUTHENTICATED, self::ADMIN_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();
    }

	/*
	* Create Vehicle Localization for tests
	* @return result of post http request
	*/
	public function putUpdateUser(bool $authenticated = false, bool $commonUser = true)
	{
        // Cria um usuário qualquer
		$targetUser = $this->createCommonUser();

		// Monta o array com os dados de atualização
		$data = [
			'id' => $targetUser->id,
			'password' => 'password',
			'password_confirm' => 'password'
		];

		// Verifica se a requisição deve ser com usuário logado ou não
		if(true === $authenticated) {

			// Verifica se o usuário deve ser comum ou admin/super admin
			$loggedUser = (true === $commonUser ? $this->createCommonUser() : $this->createAdminUser());

			// Retorna a resposta da requisição feita com usuário logado
			return $this->actingAs($loggedUser)->putJson('/admin/users/' .$targetUser->id. '/password', $data);
		}
		// Retorna a resposta da requisição feita sem usuário logado
		return $this->putJson('/admin/users/' .$targetUser->id. '/password', $data);
	}
}
