<?php

namespace Tests\Feature\Vehicle;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCompanyInformationTest extends TestCase
{
    use RefreshDatabase, WithFaker;
/*
    public function test_update_company_information_with_not_authenticated_user()
    {
		// Faz a requisição para atualizar o registro sem informar usuário logado
		$response = $this->putUpdateCompanyInformation( self::NOT_AUTHENTICATED );

		// Verifica se o usuário está logado
		$this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function test_update_company_information_with_admin_user()
    {
		// Faz a requisição para atualizar o registro com usuário comum
		$response = $this->putUpdateCompanyInformation( self::AUTHENTICATED, self::ADMIN_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
                'id',
                'owner_user_id',
                'name'
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
	/*
	public function putUpdateCompanyInformation(bool $authenticated = false, bool $commonUser = true)
	{
		// Verifica se a requisição deve ser com usuário logado ou não
		if(true === $authenticated) {

			// Verifica se o usuário deve ser comum ou admin/super admin
			$user = (true === $commonUser ? $this->createCommonUser() : $this->createAdminUser());

			// Faz login
			Auth::loginUsingId($user->id);

			// Especifica o ID do usuário responsável pela requisição
			$data['user_id'] = $user->id;

			// Retorna a resposta da requisição feita com usuário logado
			return $this->actingAs($user)->putJson('/vehicle/localizations/'.$localization->id, $data);
		}
		// Retorna a resposta da requisição feita sem usuário logado
		return $this->putJson('/companies/localizations/'.$localization->id, $data);
	}
	*/
}
