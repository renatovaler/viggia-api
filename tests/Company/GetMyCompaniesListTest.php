<?php

namespace Tests\Company;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetMyCompaniesListTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_my_companies_list_with_not_authenticated_user()
    {
		// Cria um usuário qualquer
		$user = $this->createCommonUser();

        // Cria empresas
		$this->createCompanies($user->id, 15);
		
		// Faz a requisição para obter a lista de empresas sem informar usuário logado
        $response = $this->getJson('/companies/current-user');
		
		// Verifica se o usuário não está logado
        $this->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function test_get_my_companies_list_with_authenticated_user_with_empty_companies()
    {
		// Cria um usuário a ser logado
		$loggedUser = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($loggedUser->id);

		/*
		* Faz a requisição para obter a lista de empresas de outro usuário
		* Usuário logado: SIM
		* Membro da empresa: NÃO
		* Parametros de update válidos: PREJUDICADO
		*/
		$response = $this->actingAs(auth()->user())->getJson('/companies/current-user');

		// Verifica se o usuário não está logado
        $this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure(['data']);

		// Verifica se a quantidade de itens no JSON de resposta é zero
		$response->assertJsonCount(0, 'data');
    }

    public function test_get_my_companies_list_with_authenticated_user_with_companies()
    {
		// Cria um usuário a ser logado
		$loggedUser = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($loggedUser->id);

        // Cria empresas de outro usuário
		$this->createCompanies($loggedUser->id, 15);

		/*
		* Faz a requisição para obter a lista de empresas de outro usuário
		* Usuário logado: SIM
		* Membro da empresa: NÃO
		* Parametros de update válidos: PREJUDICADO
		*/
		$response = $this->actingAs(auth()->user())->getJson('/companies/current-user');

		// Verifica se o usuário não está logado
        $this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure(['data']);

		// Verifica se a quantidade de itens no JSON de resposta é zero
		$response->assertJsonCount(15, 'data');
    }
}
