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
	* Proprietário da empresa: PREJUDICADO
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
	* Proprietário da empresa: NÃO
	*/
    public function test_delete_company_with_authenticated_user_but_not_member_of_company()
    {
		// Cria um usuário logado
		$loggedUser = $this->createCommonUser();

		// Cria um usuário qualquer
		$anotherUser = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($loggedUser->id);

		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Cria a empresa
		$company = $this->createCompany($anotherUser->id);

		// Faz a requisição para deletar a empresa
		$response = $this->deleteJson('/companies/'.$company->id);

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
	* Proprietário da empresa: NÃO
	*/
    public function test_delete_company_with_authenticated_user_and_simple_member_of_company()
    {
		// Cria um usuário qualquer
		$ownerUser = $this->createCommonUser();

		// Cria a empresa com o Owner
		$company = $this->createCompany($ownerUser->id);

		$memberUser = $this->createCommonUser();	

		// Faz login do Owner
		Auth::loginUsingId($ownerUser->id);
		
		// Verifica se o Owner está logado
		$this->assertAuthenticated();

		// Verifica se a coluna "current_company_id" = null do Owner
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		/*
		* Tenta setar a empresa criada como "current company" do usuário Owner
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" do Owner foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Faz logout do Owner
		Auth::logout();

		// Verifica se o Owner foi mesmo deslogado
        $this->assertGuest();

		// Faz login do memberUser
		Auth::loginUsingId($memberUser->id);
		
		// Verifica se o memberUser está logado
		$this->assertAuthenticated();

		// Verifica se a coluna "current_company_id" = null do memberUser
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		// Adiciona o memberUser à company criada pelo Owner
		$company->onlyCompanyMembers()->attach($memberUser->id);

		// Verifica se o memberUser foi mesmo adicionado à company_members
		$this->assertDatabaseHas('company_members', [
			'company_id' => $company->id,
			'user_id' => $memberUser->id
		]);

		/*
		* Tenta setar a empresa criada como "current company" do usuário memberUser
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" do memberUser foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Faz a requisição para deletar a empresa usando o memberUser como usuário logado
		$response = $this->deleteJson('/companies/'.$company->id);

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();

		// Verifica se a company não foi deletada
		$this->assertDatabaseHas('companies', [
			'id' => $company->id
		]);

		// Verifica se o memberUser não foi removido da company_member
		$this->assertDatabaseHas('company_members', [
			'company_id' => $company->id,
			'user_id' => $memberUser->id
		]);

		// Verifica se a coluna "current_company_id" do memberUser continua sendo = $company->id
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);
    }
	
	/*
	* Teste de exclusão da empresa
	* Usuário logado: SIM
	* Membro da empresa: SIM
	* Proprietário da empresa: SIM
	*/
    public function test_delete_company_with_authenticated_user_and_owner_of_company()
    {
		// Cria um usuário qualquer
		$ownerUser = $this->createCommonUser();

		// Cria a empresa com o Owner
		$company = $this->createCompany($ownerUser->id);

		$memberUser = $this->createCommonUser();

		// Faz login do memberUser
		Auth::loginUsingId($memberUser->id);
		
		// Verifica se o memberUser está logado
		$this->assertAuthenticated();

		// Verifica se a coluna "current_company_id" = null do memberUser
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		// Adiciona o memberUser à company criada pelo Owner
		$company->onlyCompanyMembers()->attach($memberUser->id);

		// Verifica se o memberUser foi mesmo adicionado à company_members
		$this->assertDatabaseHas('company_members', [
			'company_id' => $company->id,
			'user_id' => $memberUser->id
		]);

		/*
		* Tenta setar a empresa criada como "current company" do usuário memberUser
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" do memberUser foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Faz logout do memberUser
		Auth::logout();

		// Verifica se o memberUser foi mesmo deslogado
        $this->assertGuest();

		// Faz login do Owner
		Auth::loginUsingId($ownerUser->id);
		
		// Verifica se o Owner está logado
		$this->assertAuthenticated();

		// Verifica se a coluna "current_company_id" = null do Owner
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		/*
		* Tenta setar a empresa criada como "current company" do usuário Owner
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" do Owner foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Faz a requisição para deletar a empresa usando o Owner como usuário logado
		$response = $this->deleteJson('/companies/'.$company->id);
        
		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();

		// Verifica se a company foi mesmo deletada
		$this->assertDatabaseMissing('companies', [
			'id' => $company->id
		]);

		// Verifica se o memberUser foi mesmo removido da company_member
		$this->assertDatabaseMissing('company_members', [
			'company_id' => $company->id,
			'user_id' => $memberUser->id
		]);

		// Verifica se a coluna "current_company_id" do Owner voltou a ser = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		// Verifica se a coluna "current_company_id" do memberUser voltou a ser = null
		$this->assertDatabaseHas('users', [
			'id' => $memberUser->id,
			'current_company_id' => null
		]);
    }
}
