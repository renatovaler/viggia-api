<?php

namespace Tests\Admin\Role;

use Tests\TestCase;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateRoleTest extends TestCase
{
    use RefreshDatabase;
	use WithFaker;

	/*
	*	Teste de criação da role com usuário não autenticado
	*	Usuário logado: NÃO
	*	Usuário autorizado (nível de acesso): PREJUDICADO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_create_role_with_not_authenticated_user()
    {
		// Verifica se o usuário não está logado
		$this->assertGuest();
		
		// Faz a requisição para criar o registro sem usuário logado
		$response = $this->postCreateRole( self::NOT_AUTHENTICATED );
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	*	Teste de criação da role com usuário autenticado, mas não autorizado
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): NÃO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_create_role_with_unauthorized_user()
    {
		// Faz a requisição para criar o registro com usuário comum
		$response = $this->postCreateRole( self::AUTHENTICATED, self::COMMON_USER );
		
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }

	/*
	*	Teste de criação da role com usuário autenticado e com autorização (nível de acesso)
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: SIM
	*/
    public function test_create_role_with_authorized_user_and_valid_params()
    {
		// Faz a requisição para criar o registro com usuário admin/super_admin
		$response = $this->postCreateRole( self::AUTHENTICATED, self::ADMIN_USER, self::VALID_PARAM );
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();
		
		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description'
            ]
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }
	
	/*
	*	Teste de criação da role com usuário autenticado e com autorização (nível de acesso)
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: SIM
	*/
    public function test_create_role_with_authorized_user_but_invalid_params()
    {
		// Faz a requisição para criar o registro com usuário admin/super_admin
		$response = $this->postCreateRole( self::AUTHENTICATED, self::ADMIN_USER, self::INVALID_PARAM );
        
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
	* Create user for tests
	* @return result of post http request
	*/
	public function postCreateRole(bool $authenticated = false, bool $commonUser = true, bool $validParam = true)
	{
		if(true === $validParam) {
			$data = [
				'name' => $this->faker->slug,
				'description' => $this->faker->text(50),
			];
		} else {
			$data = [];
		}
		
		if(true === $authenticated) {
			$user = (true === $commonUser ? $this->createCommonUser() : $this->createAdminUser());
			
			// Faz login
			Auth::loginUsingId($user->id);

			return $this->actingAs($user)->post('/admin/roles/create', $data);
		}
		
		return $this->post('/admin/roles/create', $data);
	}
}
