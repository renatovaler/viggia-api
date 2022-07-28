<?php

namespace Tests\Admin\Role;

use App\Role\Models\Role;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetRoleInformationTest extends TestCase
{
	use WithFaker;
    use RefreshDatabase;

	/*
	* Teste - buscar dados de uma role com usuário não logado
	* Usuário logado: NÃO
	* Usuário autorizado: PREJUDICADO
	* Parâmetros corretos: PREJUDICADO
	*/
    public function test_get_role_information_with_not_authenticated_user()
    {
        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);

		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->getJson('/admin/roles/'. $role->id);

		// Verifica se o usuário não está logado
        $this->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	* Teste - buscar dados de uma role com usuário logado, autorizado e com parâmetros válidos
	* Usuário logado: SIM
	* Usuário autorizado: SIM
	* Parâmetros corretos: SIM
	*/
    public function test_get_role_information_with_authorized_user_and_valid_params()
    {
        // Cria um usuário admin
        $user = $this->createAdminUser();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);
        
		// Faz login
		Auth::loginUsingId($user->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->actingAs($user)->getJson('/admin/roles/'. $role->id);
        
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
	* Teste - buscar dados de uma role com usuário logado, autorizado, mas com parâmetro inválido
	* Usuário logado: SIM
	* Usuário autorizado: SIM
	* Parâmetros corretos: NÃO
	*/
    public function test_get_role_information_with_authorized_user_but_invalid_params()
    {
        // Cria um usuário admin
        $user = $this->createAdminUser();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);
        
		// Faz login
		Auth::loginUsingId($user->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Faz a requisição para obter os dados do registro especificando uma url inválida (param XXXXX)
        $response = $this->actingAs($user)->getJson('/admin/roles/XXXXX');
        
		// Verifica se a role ainda existe
		$this->assertDatabaseHas('roles', ['id' => $role->id]);

		// Verifica se o código de resposta HTTP está correto (404)
		$response->assertNotFound();
    }

	/*
	*
	* >>> TESTE DESATIVADO - POR ORA É DESNECESSÁRIO
	* >>> A PRINCÍPIO, TODOS PODERÃO "VER" A INFORMAÇÃO DE UMA ROLE
	*
	* Teste - buscar dados de uma role com usuário logado, mas não autorizado
	* Usuário logado: SIM
	* Usuário autorizado: NÃO
	* Parâmetros corretos: SIM
	*/
    public function ______test_get_role_information_with_unauthorized_user()
    {
        // Cria um usuário comum
        $commonUser = $this->createCommonUser();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);
        
		// Faz login
		Auth::loginUsingId($commonUser->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->actingAs($commonUser)->getJson('/admin/roles/'. $role->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }
}
