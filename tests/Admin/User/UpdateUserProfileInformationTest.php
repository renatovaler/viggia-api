<?php

namespace Tests\Admin\User;

use App\User\Models\User;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserProfileInformationTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

	/*
	*	Teste de update de um user com usuário não logado
	*	Usuário logado: NÃO
	*	Usuário autorizado (nível de acesso): PREJUDICADO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_update_user_profile_with_not_authenticated_user()
    {
		// Verifica se o usuário não está logado
		$this->assertGuest();

        // Cria um usuário qualquer
		$targetUser = $this->createCommonUser();

		// Faz a requisição para atualizar o registro sem informar usuário logado
		$response = $this->putJson('/admin/users/' .$targetUser->id. '/profile', []);

		// Verifica se os dados continuam igual, sem atualizar
		$this->assertDatabaseHas('users', [
			'id' => $targetUser->id,
			'name' => $targetUser->name,
			'email' => $targetUser->email,
			'email_verified_at' => $targetUser->email_verified_at
		]);
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	*	Teste de update de um user com usuário logado, mas não autorizado
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): NÃO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_update_user_profile_with_authenticated_but_unauthorized_user()
    {
        // Cria um usuário comum [logged]
        $loggedUser = $this->createCommonUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);
        
		// Verifica se o usuário "loggedUser" está logado
		$this->assertAuthenticated();

        // Cria um usuário qualquer [target]
        $targetUser = $this->createCommonUser();

		// Array com parâmetros de atualização
		$data = [
			'id' => $targetUser->id,
			'name' => 'teste',
			'email' => $targetUser->email
		];
		
		// Faz a requisição para atualizar o registro informando usuário logado
		$response = $this->actingAs($loggedUser)->putJson('/admin/users/' .$targetUser->id. '/profile', $data);

		// Verifica se os dados continuam igual, sem atualizar
		$this->assertDatabaseHas('users', [
			'id' => $targetUser->id,
			'name' => $targetUser->name,
			'email' => $targetUser->email,
			'email_verified_at' => $targetUser->email_verified_at
		]);

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }

	/*
	*	Teste de update de um user com usuário logado, autorizado, mas sim parâmetros inválidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: NÃO
	*/
    public function test_update_user_profile_with_authenticated_and_authorized_user_but_invalid_params()
    {
        // Cria um usuário admin [logged]
        $loggedUser = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);
        
		// Verifica se o usuário "loggedUser" está logado
		$this->assertAuthenticated();

        // Cria um usuário qualquer [target]
        $targetUser = $this->createCommonUser();
		
		// Faz a requisição para atualizar o registro informando usuário logado e parâmetros de atualização inválidos
		$response = $this->actingAs($loggedUser)->putJson('/admin/users/' .$targetUser->id. '/profile', []);

		// Verifica se os dados continuam igual, sem atualizar
		$this->assertDatabaseHas('users', [
			'id' => $targetUser->id,
			'name' => $targetUser->name,
			'email' => $targetUser->email,
		]);

		// Verifica se está correta a estrutura do JSON de resposta de erro de validação
        $response->assertJsonStructure([
			'message',
            'errors' => []
		]);
		
		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();
    }

	/*
	*	Teste de update de um user com usuário logado, autorizado e com parâmetros válidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: SIM
	*/
    public function test_update_user_profile_with_authenticated_and_authorized_user_and_valid_params()
    {
        // Cria um usuário admin [logged]
        $loggedUser = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);
        
		// Verifica se o usuário "loggedUser" está logado
		$this->assertAuthenticated();

        // Cria um usuário qualquer [target]
        $targetUser = $this->createCommonUser();

		// Array com parâmetros de atualização
		$data = [
			'id' => $targetUser->id,
			'name' => 'teste',
			'email' => 'teste@email.com'
		];
		
		// Faz a requisição para atualizar o registro informando usuário logado
		$response = $this->actingAs($loggedUser)->putJson('/admin/users/' .$targetUser->id. '/profile', $data);

		// Verifica se os dados foram atualizados
		$this->assertDatabaseHas('users', [
			'id' => $targetUser->id,
			'name' => 'teste',
			'email' => 'teste@email.com'
		]);

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
}
