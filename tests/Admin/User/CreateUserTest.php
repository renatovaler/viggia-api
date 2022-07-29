<?php
namespace Tests\Admin\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserTest extends TestCase
{
	use WithFaker;
    use RefreshDatabase;

	/*
	*	Teste de criação de usuário com usuário não autenticado
	*	Usuário logado: NÃO
	*	Usuário autorizado (nível de acesso): PREJUDICADO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_create_new_user_with_not_authenticated_user()
    {
		// Faz a requisição para criar o registro sem usuário logado
		$response = $this->postCreateUser( self::NOT_AUTHENTICATED );

		// Verifica se o usuário não está logado
        $this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	*	Teste de criação de usuário com usuário logado, mas não autorizado
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): NÃO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_create_new_user_with_not_authorized_user()
    {
		// Faz a requisição para criar o registro com usuário comum
		$response = $this->postCreateUser( self::AUTHENTICATED, self::COMMON_USER );

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }

	/*
	*	Teste de criação de usuário com usuário logado, autorizado, mas com parâmetros inválidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: NÃO
	*/
    public function test_create_new_user_with_authorized_user_but_invalid_params()
    {
		// Faz a requisição para criar o registro com usuário admin/super_admin
		$response = $this->postCreateUser( self::AUTHENTICATED, self::ADMIN_USER, self::INVALID_PARAM );

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
	*	Teste de criação de usuário com usuário logado, autorizado e com parâmetros válidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: SIM
	*/
    public function test_create_new_user_with_authorized_user_and_valid_params()
    {
		// Faz a requisição para criar o registro com usuário admin/super_admin
		$response = $this->postCreateUser( self::AUTHENTICATED, self::ADMIN_USER, self::VALID_PARAM );

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
	public function postCreateUser(bool $authenticated = false, bool $commonUser = true, bool $validParam = true)
	{
		$data = [];
		if(true === $validParam) {
			$data = [
				'name' => $this->faker->name,
				'email' => $this->faker->unique()->safeEmail(),
				'password' => 'password',
				'password_confirmation' => 'password'	
			];
		}
		if(true === $authenticated) {
			$user = (true === $commonUser ? $this->createCommonUser() : $this->createAdminUser());
			return $this->actingAs($user)->post('/admin/users/create', $data);
		}

		return $this->post('/admin/users/create', $data);
	}
}
