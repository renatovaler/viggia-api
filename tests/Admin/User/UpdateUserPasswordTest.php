<?php

namespace Tests\Admin\User;

use App\User\Models\User;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserPasswordTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

	/*
	*	Teste de update de um user password com usuário não logado
	*	Usuário logado: NÃO
	*	Usuário autorizado (nível de acesso): PREJUDICADO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_update_user_password_with_not_authenticated_user()
    {
		// Verifica se o usuário não está logado
		$this->assertGuest();

		// Gera o hash do password para posterior verificação
		$password = Hash::make('password');

        // Cria um usuário qualquer
		$targetUser = $this->createCommonUser();

		// Seta o password
		$targetUser->password = $password;
		$targetUser->save();

		// Faz a requisição para atualizar o registro sem informar usuário logado
		$response = $this->putJson('/admin/users/' .$targetUser->id. '/password', []);

		// Faz um reload dos dados do targetUser após o request de update
		$targetUser = User::find($targetUser->id);

		// Verifica se os dados continuam igual, sem atualizar a password
		$this->assertTrue(
			Hash::check('password', $targetUser->password)
		);
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	*	Teste de update de um user password com usuário logado, mas não autorizado
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): NÃO
	*	Parâmetros de request válidos: PREJUDICADO
	*/
    public function test_update_user_password_with_authenticated_but_unauthorized_user()
    {
        // Cria um usuário comum [logged]
        $loggedUser = $this->createCommonUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);
        
		// Verifica se o usuário "loggedUser" está logado
		$this->assertAuthenticated();

		// Gera o hash do password para posterior verificação
		$password = Hash::make('password');

        // Cria um usuário qualquer [target]
        $targetUser = $this->createCommonUser();

		// Seta o password do user target
		$targetUser->password = $password;
		$targetUser->save();

		// Array com parâmetros de atualização
		$data = [
			'id' => $targetUser->id,
            'current_password' => 'password',
			'password' => 'password2',
			'password_confirmation' => 'password2'
		];
		
		// Faz a requisição para atualizar o registro informando usuário logado
		$response = $this->actingAs($loggedUser)->putJson('/admin/users/' .$targetUser->id. '/password', $data);

		// Faz um reload dos dados do targetUser após o request de update
		$targetUser = User::find($targetUser->id);

		// Verifica se os dados continuam igual, sem atualizar a password
		$this->assertTrue(
			Hash::check('password', $targetUser->password)
		);

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }

	/*
	*	Teste de update de um user password com usuário logado, autorizado, mas sim parâmetros inválidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: NÃO
	*/
    public function test_update_user_password_with_authenticated_and_authorized_user_but_invalid_params()
    {
        // Cria um usuário admin [logged]
        $loggedUser = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);
        
		// Verifica se o usuário "loggedUser" está logado
		$this->assertAuthenticated();

		// Gera o hash do password para posterior verificação
		$password = Hash::make('password');

        // Cria um usuário qualquer [target]
        $targetUser = $this->createCommonUser();

		// Seta o password do user target
		$targetUser->password = $password;
		$targetUser->save();
		
		// Faz a requisição para atualizar o registro informando usuário logado
		$response = $this->actingAs($loggedUser)->putJson('/admin/users/' .$targetUser->id. '/password', []);

		// Faz um reload dos dados do targetUser após o request de update
		$targetUser = User::find($targetUser->id);

		// Verifica se os dados continuam igual, sem atualizar a password
		$this->assertTrue(
			Hash::check('password', $targetUser->password)
		);

		// Verifica se está correta a estrutura do JSON de resposta de erro de validação
        $response->assertJsonStructure([
			'message',
            'errors' => []
		]);
		
		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();
    }

	/*
	*	Teste de update de um user password com usuário logado, autorizado e com parâmetros válidos
	*	Usuário logado: SIM
	*	Usuário autorizado (nível de acesso): SIM
	*	Parâmetros de request válidos: SIM
	*/
    public function test_update_user_password_with_authenticated_and_authorized_user_and_valid_params()
    {
        // Cria um usuário admin [logged]
        $loggedUser = $this->createAdminUser();
        
		// Faz login
		Auth::loginUsingId($loggedUser->id);
        
		// Verifica se o usuário "loggedUser" está logado
		$this->assertAuthenticated();

		// Gera o hash do password para posterior verificação
		$password = Hash::make('password');

        // Cria um usuário qualquer [target]
        $targetUser = $this->createCommonUser();

		// Seta o password do user target
		$targetUser->password = $password;
		$targetUser->save();

		// Array com parâmetros de atualização
		$data = [
			'id' => $targetUser->id,
            'current_password' => 'password',
			'password' => 'password2',
			'password_confirmation' => 'password2'
		];
		
		// Faz a requisição para atualizar o registro informando usuário logado
		$response = $this->actingAs($loggedUser)->putJson('/admin/users/' .$targetUser->id. '/password', $data);

		// Faz um reload dos dados do targetUser após o update anterior
		$targetUser = User::find($targetUser->id);

		// Verifica se o password do targetUser foi atualizado
		$this->assertTrue(
			Hash::check('password2', $targetUser->password)
		);

		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();
    }
}
