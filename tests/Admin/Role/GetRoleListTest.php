<?php

namespace Tests\Admin\Role;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetRoleListTest extends TestCase
{
    use RefreshDatabase;
/*
    public function test_get_user_list_with_not_authenticated_user()
    {
        // Cria usuários
		$this->createUsers(15);
		
		// Faz a requisição para obter os dados dos registros sem informar usuário logado
        $response = $this->getJson('/admin/users');
		
		// Verifica se o usuário não está logado
        $this->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function test_get_user_list_with_common_user()
    {
        // Cria um usuário comum (não admin)
        $user = $this->createCommonUser();
		
        // Cria usuários
		$this->createUsers(15);
		
		// Faz a requisição para obter os dados dos registros informando um usuário comum logado
        $response = $this->actingAs($user)->getJson('/admin/users');
        
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

    public function test_get_user_list_with_admin_user()
    {
        // Cria um usuário admin e super_admin
        $user = $this->createAdminUser();
		
        // Cria usuários
		$this->createUsers(15);
		
		// Faz a requisição para obter os dados dos registros informando um usuário admin/super_admin logado
        $response = $this->actingAs($user)->getJson('/admin/users');
        
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
*/
}
