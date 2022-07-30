<?php

namespace Tests\Admin\User;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserListTest extends TestCase
{
    use RefreshDatabase;

	/*
	*	Teste de obtenção da lista de usuários, sem informar usuário logado
	*	Usuário logado: NÃO
	*	Usuário autorizado (nível de acesso): NÃO
	*/
    public function test_get_user_list_with_not_authenticated_user()
    {
		// Verifica se o usuário não está logado
        $this->assertGuest();

        // Cria lista de usuários
		$this->createUsers(15);
		
		// Faz a requisição para obter os registros sem informar usuário logado
        $response = $this->getJson('/admin/users');
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	*	Teste de obtenção da lista de usuários, informando usuário logado, mas não autorizado
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): NÃO
	*/
    public function test_get_user_list_with_authenticated_but_unauthorized_user()
    {
        // Cria um usuário comum (não admin)
        $user = $this->createCommonUser();
        
		// Faz login
		Auth::loginUsingId($user->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();
		
        // Cria lista de usuários
		$this->createUsers(15);
		
		// Faz a requisição para obter os registros informando um usuário comum logado
        $response = $this->actingAs($user)->getJson('/admin/users');

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }

	/*
	*	Teste de obtenção da lista de usuários, informando usuário logado e autorizado
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*/
    public function test_get_user_list_with_authenticated_and_authorized_user()
    {
        // Cria um usuário admin e super_admin
        $user = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($user->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();
		
        // Cria lista de usuários
		$this->createUsers(15);
		
		// Faz a requisição para obter os registros informando um usuário comum logado
        $response = $this->actingAs($user)->getJson('/admin/users');
		
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
}
