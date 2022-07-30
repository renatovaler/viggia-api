<?php

namespace Tests\Admin\User;

use App\User\Models\User;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserProfileInformationTest extends TestCase
{
    use RefreshDatabase;

	/*
	*	Teste - buscar dados do perfil de um user com usuário não logado
	*	Usuário logado: NÃO
	*	Usuário autorizado (nível de acesso): PREJUDICADO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_get_user_profile_with_not_authenticated_user()
    {
        // Cria o usuário a ser pesquisado [target]
		$targetUser = $this->createCommonUser();

		// Faz a requisição para obter os dados dos registros sem informar usuário logado
        $response = $this->getJson('/admin/users/'. $targetUser->id . '/profile');

		// Verifica se o usuário não está logado
        $this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	*	Teste - buscar dados do perfil de um user com usuário logado, mas não autorizado
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): NÃO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_get_user_profile_with_authenticated_but_unauthorized_user()
    {
        // Cria um usuário comum (não admin)
        $loggedUser = $this->createCommonUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

        // Cria o usuário a ser pesquisado [target]
		$targetUser = $this->createCommonUser();

		// Faz a requisição para obter os dados dos registros informando um usuário comum logado
        $response = $this->actingAs($loggedUser)->getJson('/admin/users/'. $targetUser->id . '/profile');

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }

	/*
	*	Teste - buscar dados do perfil de um user com usuário logado e autorizado, mas com parâmetros inválidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: NÃO
	*	Parâmetros de request inválido enviado: user_id do tipo "string" (esperado, int)
	*/
    public function test_get_user_profile_with_authenticated_and_authorized_user_but_invalid_params()
    {
        // Cria um usuário admin/super_admin
        $loggedUser = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

        // Cria o usuário a ser pesquisado [target]
		$targetUser = $this->createCommonUser();

		/*
		* Faz a requisição para obter os dados dos registros
		* -> informando um usuário admin/super_admin logado
		* -> parâmetros de requisição inválidos (tipo enviado: string, esperado: int)
        */
		$response = $this->actingAs($loggedUser)->getJson('/admin/users/XXXXXX/profile');

		// Verifica se o código de resposta HTTP está correto (404)
		$response->assertNotFound();
    }

	/*
	*	Teste - buscar dados do perfil de um user com usuário logado e autorizado, informando user id que não existe
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: NÃO
	*	Parâmetros de request inválido enviado: user_id inexistente
	*/
    public function test_get_user_profile_with_authenticated_and_authorized_user_but_inexistent_user_id()
    {
        // Cria um usuário admin/super_admin
        $loggedUser = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Pega o último id de usuário existente e soma + 1
		$id = (int) User::latest()->first()->id + 1;
		
		// Verifica se não existe esse user 
		$this->assertDatabaseMissing('users', ['id' => $id ]);

		/*
		* Faz a requisição para obter os dados dos registros
		* -> informando um usuário admin/super_admin logado
		* -> parâmetros de requisição inválidos
		* -> busca usuário com id inexistente
        */
		$response = $this->actingAs($loggedUser)->getJson('/admin/users/'. $id . '/profile');

		// Verifica se o código de resposta HTTP está correto (404)
		$response->assertNotFound();
    }

	/*
	*	Teste - buscar dados do perfil de um user com usuário logado e autorizado, com parâmetros válidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: SIM
	*/
    public function test_get_user_profile_with_authenticated_and_authorized_user_and_valid_params()
    {
        // Cria um usuário admin/super_admin
        $loggedUser = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

        // Cria o usuário a ser pesquisado [target]
		$targetUser = $this->createCommonUser();

		// Faz a requisição para obter os dados dos registros informando um usuário admin/super_admin logado
        $response = $this->actingAs($loggedUser)->getJson('/admin/users/'. $targetUser->id . '/profile');

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
