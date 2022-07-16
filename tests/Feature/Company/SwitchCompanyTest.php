<?php

namespace Tests\Feature\Company;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SwitchCompanyTest extends TestCase
{
    use RefreshDatabase;

	/*
	* Teste de troca de empresa sem usuário logado
	*/
    public function test_switch_company_with_not_authenticated_user()
    {		
		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->putJson('/companies/switch', []);
		
		// Verifica se o usuário não está logado
        $this->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	* Teste de troca de empresa com usuário logado, mas que não é membro da empresa
	*/
    public function test_switch_company_with_authenticated_user_but_not_member_of_company()
    {
        // Cria um usuário admin
        $userAdmin = $this->createAdminUser();

        // Cria um usuário comum (não admin)
        $userCommon = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($userAdmin->id);

		// Cria a empresa
		$company = $this->createCompany($userCommon->id);

		// Verifica se a coluna "current_company_id" = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->actingAs(Auth::user())->putJson('/companies/switch', ['company_id' => $company->id]);

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
	* Teste de troca de empresa com usuário logado e que é membro da empresa
	*/
    public function test_switch_company_with_authenticated_user_and_member_of_company()
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

		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->putJson('/companies/switch', ['company_id' => $company->id]);

		// Verifica se a coluna "current_company_id" foi atualizada de null para $company->id
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);
		
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
