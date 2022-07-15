<?php

namespace Tests\Feature\Vehicle;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCompanyInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_company_information_with_authenticated_user_and_member_of_company()
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
		$user->switchCompany($company);

		// Verifica se a coluna "current_company_id" foi atualizada de null para $company->id
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Requisita informações da company
        $response = $this->actingAs($user)->getJson('companies/current-company/profile');

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
