<?php

namespace Tests\Feature\MyselfUser;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateMyselfPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_myself_user_password_with_not_authenticated_user()
    {
		// Faz a requisição para atualizar o registro SEM usuário logado
		$response = $this->putUpdateUserPassword( self::NOT_AUTHENTICATED );
        
		// Verifica se o usuário está logado
		$response->assertAuthenticated();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function test_update_myself_user_password_with_authenticated_user()
    {
		// Faz a requisição para atualizar o registro COM usuário logado
		$response = $this->putUpdateUserPassword( self::AUTHENTICATED );
        
		// Verifica se o usuário está logado
		$response->assertAuthenticated();
        
		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();
    }
	
	/*
	* Create Vehicle Localization for tests
	* @return result of post http request
	*/
	public function putUpdateUserPassword(bool $authenticated = false, bool $commonUser = true)
	{
        // Cria um usuário qualquer
		$loggedUser = $this->createUser();
		
		// Monta o array com os dados de atualização
		$data = [
			'id' => $loggedUser->id,
			'password' => 'password',
			'password_confirm' => 'password'
		];
		
		// Verifica se a requisição deve ser com usuário logado ou não
		if(true === $authenticated) {
			
			// Retorna a resposta da requisição feita COM usuário logado
			return $this->actingAs($loggedUser)->putJson('/myself/password', $data);
		}
		
		// Retorna a resposta da requisição feita SEM usuário logado
		return $this->putJson('/myself/password', $data);
	}
}