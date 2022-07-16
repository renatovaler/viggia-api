<?php

namespace Tests\Feature\Company;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_create_company_with_not_authenticated_user()
    {
		// Faz a requisição para criar o registro sem usuário logado
		$response = $this->postCreateCompany( self::NOT_AUTHENTICATED );

		// Verifica se o usuário não está logado
        $this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function test_create_company_with_authorized_user()
    {
		// Faz a requisição para criar o registro com usuário admin/super_admin
		$response = $this->postCreateCompany( self::AUTHENTICATED, self::ADMIN_USER, self::VALID_PARAM);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
                'id',
                'user_id',
                'name'
			]
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }

    public function test_create_company_with_authorized_user_but_invalid_params()
    {
		// Faz a requisição para criar o registro com usuário admin/super_admin
		$response = $this->postCreateCompany( self::AUTHENTICATED, self::ADMIN_USER, self::INVALID_PARAM);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'message',
            'errors' => []
		]);
		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();
    }

	/*
	* Create company for tests
	* @return result of post http request
	*/
	public function postCreateCompany(bool $authenticated = false, bool $commonUser = true, bool $validParam = true)
	{
        if( false === $validParam ) {
            $data = [];
        } else {
            $data = [
				'name' => $this->faker->name(),
            ];
        }

		if(true === $authenticated) {

			$user = (true === $commonUser ? $this->createCommonUser() : $this->createAdminUser());

            // Faz login
            Auth::loginUsingId($user->id);

			$data['user_id'] = $user->id;

			return $this->actingAs(Auth::user())->post('/companies/create', $data);
		}

		return $this->post('/companies/create', $data);
	}
}
