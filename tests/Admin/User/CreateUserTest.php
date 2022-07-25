<?php

namespace Tests\Admin\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CreateUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function _test_create_new_user_with_not_authenticated_user()
    {
		// Faz a requisição para criar o registro sem usuário logado
		$response = $this->postCreateUser( self::NOT_AUTHENTICATED );

		// Verifica se o usuário não está logado
        $this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function _test_create_new_user_with_common_user()
    {
		// Faz a requisição para criar o registro com usuário comum
		$response = $this->postCreateUser( self::AUTHENTICATED, self::COMMON_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

    public function _test_create_new_user_with_admin_user()
    {
		// Faz a requisição para criar o registro com usuário admin/super_admin
		$response = $this->postCreateUser( self::AUTHENTICATED, self::ADMIN_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at'
            ]
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }

	/*
	* Create user for tests
	* @return result of post http request
	*/
	public function postCreateUser(bool $authenticated = false, bool $commonUser = true)
	{
		$data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password'
		];

		if(true === $authenticated) {
			$user = (true === $commonUser ? $this->createCommonUser() : $this->createAdminUser());
			return $this->actingAs($user)->post('/admin/users/create', $data);
		}

		return $this->post('/admin/users/create', $data);
	}
}
