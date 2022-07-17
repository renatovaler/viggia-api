<?php

namespace Tests\Feature\Company;

use App\Domain\User\Models\User;
use App\Domain\Company\Models\Company;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCompanyTest extends TestCase
{
    use RefreshDatabase;
	use WithFaker;
	
	/*
	* Teste de exclusão da empresa
	* Usuário logado: NÃO
	* Membro da empresa: PREJUDICADO
	*/
    public function test_delete_company_with_not_authenticated_user()
    {
		// Cria um usuário logado
		$user = $this->createCommonUser();

		// Cria a empresa
		$company = $this->createCompany($user->id);

		// Faz a requisição para deletar a empresa
		$response = $this->deleteJson('/companies/'.$company->id);

		// Verifica se o usuário está logado
		$this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

	/*
	* Teste de exclusão da empresa
	* Usuário logado: SIM
	* Membro da empresa: NÃO
	*/
    public function test_delete_company_with_authenticated_user_but_not_member_of_company()
    {
		// Cria um usuário logado
		$loggedUser = $this->createCommonUser();

		// Cria um usuário qualquer
		$anotherUser = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($loggedUser->id);

		// Cria a empresa
		$company = $this->createCompany($anotherUser->id);

		// Faz a requisição para deletar a empresa
		$response = $this->deleteJson('/companies/'.$company->id);

		// Verifica se o usuário não está logado
        $this->assertAuthenticated();

		// Verifica se a coluna "current_company_id" continua = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
	}

	/*
	* Teste de exclusão da empresa
	* Usuário logado: SIM
	* Membro da empresa: SIM
	*/
    public function test_delete_company_with_authenticated_user_and_member_of_company()
    {
		// Cria um usuário qualquer
		$user = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($user->id);

		// Verifica se a coluna "current_company_id" = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		// Cria a empresa
		$company = $this->createCompany($user->id);

		/*
		* Tenta setar a empresa criada como "current company" do usuário logado
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Faz a requisição para deletar a empresa
		$response = $this->deleteJson('/companies/'.$company->id);

		// Verifica se a coluna "current_company_id" voltou a ser = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);
        
		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();
    }
}
