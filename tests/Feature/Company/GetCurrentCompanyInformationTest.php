<?php

namespace Tests\Feature\Company;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCurrentCompanyInformationTest extends TestCase
{
    use RefreshDatabase;

	/*
	* Teste de atualização dos dados da empresa "atual" com usuário NÃO logado.
	*/
    public function test_get_current_company_information_with_not_authenticated_user()
    {
		// Requisita informações da company
        $response = $this->getJson('companies/current-company/profile');

		// Verifica se o usuário está logado
		$this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
	}

	/*
	* Teste de atualização dos dados da empresa "atual" com usuário logado, mas que não é membro da empresa
	*/
    public function test_get_current_company_information_with_authenticated_user_but_not_member_of_company()
    {
        // Cria um usuário comum (não admin)
        $loggedUser = $this->createCommonUser();

        // Cria um usuário comum (não admin)
        $anotherUser = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($loggedUser->id);
		
		// Verifica se o usuário está logado
        $this->assertAuthenticated();

		// Cria a empresa com usuário diverso do logado
		$company = $this->createCompany($anotherUser->id);

		// Verifica se a coluna "current_company_id" do usuário logado = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		// Requisita informações da company
        $response = $this->actingAs(Auth::user())->getJson('companies/current-company/profile');

		// Verifica se a resposta foi do tipo "NotFound" (404)
		$response->assertNotFound();
	}

	/*
	* Teste de atualização dos dados da empresa "atual" com usuário logado e que é membro da empresa
	*/
    public function test_get_current_company_information_with_authenticated_user_and_member_of_company()
    {
        // Cria um usuário comum (não admin)
        $user = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($user->id);
		
		// Verifica se o usuário está logado
        $this->assertAuthenticated();

		// Cria a empresa
		$company = $this->createCompany($user->id);

		// Verifica se a coluna "current_company_id" = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		// Alterna a company
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" foi atualizada de null para $company->id
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Requisita informações da company
        $response = $this->actingAs(Auth::user())->getJson('companies/current-company/profile');

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
                'id',
                'user_id',
                'name'
			]
		]);
		
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }
}
