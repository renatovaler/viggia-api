<?php

namespace Tests\Admin\Role;

use App\Role\Models\Role;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetRoleListTest extends TestCase
{
    use RefreshDatabase;

	/*
	* Teste - buscar lista de roles com usuário não logado
	* Usuário logado: NÃO
	*/
    public function test_get_role_list_with_not_authenticated_user()
    {
		// Cria 10 roles
		(new Role())->factory(10)->create();

		// Verifica se o usuário não está logado
        $this->assertGuest();

		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->getJson('/admin/roles');
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	* Teste - buscar lista de roles com usuário logado
	* Usuário logado: SIM
	*/
    public function test_get_role_list_with_with_authenticated_user()
    {
        // Cria um usuário admin
        $user = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($user->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Cria 10 roles
		(new Role())->factory(10)->create();

		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->actingAs($user)->getJson('/admin/roles');
		
		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
				[
					'id',
					'name',
					'description'
				]
			]
		]);
		
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }	
}
