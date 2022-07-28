<?php

namespace Tests\Admin\Role;

use App\Role\Models\Role;

use Tests\TestCase;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateRoleInformationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

	/*
	*	Teste de update da role com usuário não logado
	*	Usuário logado: NÃO
	*	Usuário autorizado (nível de acesso): PREJUDICADO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_update_role_with_not_authenticated_user()
    {
		// Verifica se o usuário não está logado
		$this->assertGuest();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);
		
		// Faz a requisição para atualizar o registro sem usuário logado
		$response = $this->putJson('/admin/roles/'.$role->id, []);
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	*	Teste de update da role com usuário logado, mas não autorizado
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): NÃO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_update_role_with_authenticated_but_unauthorized_user()
    {
        // Cria um usuário comum
        $commonUser = $this->createCommonUser();
        
		// Faz login
		Auth::loginUsingId($commonUser->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);

		$data = [
			'id' => $role->id,
            'name' => 'role-update-test',
            'description' => 'role update test description',
		];
		
		// Faz a requisição para atualizar o registro com usuário logado e não autorizado
		$response = $this->actingAs($commonUser)->putJson('/admin/roles/'.$role->id, $data);

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }

	/*
	*	Teste de update da role com usuário logado, autorizado, mas com parâmetros inválidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: NÃO
	*/
    public function test_update_role_with_authenticated_and_authorized_user_but_invalid_params()
    {
        // Cria um usuário admin
        $adminUser = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($adminUser->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);
		
		// Faz a requisição para atualizar o registro com usuário logado e não autorizado
		$response = $this->actingAs($adminUser)->putJson('/admin/roles/'.$role->id, []);

		// Verifica se está correta a estrutura do JSON de resposta com erro de validação
        $response->assertJsonStructure([
			'message',
            'errors' => []
		]);
		
		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();
    }
	

	/*
	*	Teste de update da role com usuário logado, autorizado e com parâmetros válidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: SIM
	*/
    public function test_update_role_with_authenticated_and_authorized_user_and_valid_params()
    {
        // Cria um usuário admin
        $adminUser = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($adminUser->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);

		$data = [
			'id' => $role->id,
            'name' => 'role-update-test',
            'description' => 'role update test description',
		];
		
		// Faz a requisição para atualizar o registro com usuário logado e não autorizado
		$response = $this->actingAs($adminUser)->putJson('/admin/roles/'.$role->id, $data);

		// Verifica se os dados foram atualizados
		$this->assertDatabaseHas('roles', [
			'id' => $role->id,
            'name' => 'role-update-test',
            'description' => 'role update test description',
		]);

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
}
