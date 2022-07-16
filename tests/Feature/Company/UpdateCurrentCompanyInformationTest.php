<?php

namespace Tests\Feature\Company;

use App\Domain\User\Models\User;
use App\Domain\Company\Models\Company;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCurrentCompanyInformationTest extends TestCase
{
    use RefreshDatabase;
	use WithFaker;
	
	/*
	* Teste de atualização dos dados da empresa "atual" com usuário NÃO logado.
	*/
    public function test_update_current_company_information_with_not_authenticated_user()
    {
		/*
		* Faz a requisição para atualizar os dados da empresa "atual"
		* Usuário logado: NÃO
		* Membro da empresa: PREJUDICADO
		* Parametros de update válidos: PREJUDICADO
		*/
		$response = $this->putJson('/companies/current-company/profile', []);

		// Verifica se o usuário está logado
		$this->assertGuest();

		// Verifica se a resposta foi do tipo "não autorizado" (401)
        $response->assertUnauthorized();
    }

	/*
	* Teste de atualização dos dados da empresa "atual" com usuário logado, mas que não é membro da empresa
	*/
    public function _test_update_current_company_information_with_authenticated_user_but_not_member_of_company()
    {
		// Cria um usuário logado
		$loggedUser = $this->createAdminUser();

		// Cria um usuário qualquer
		$anotherUser = $this->createAdminUser();

		// Faz login
		Auth::loginUsingId($loggedUser->id);

		/*
		* Faz a requisição para atualizar os dados da empresa "atual"
		* Usuário logado: SIM
		* Membro da empresa: NÃO
		* Parametros de update válidos: PREJUDICADO
		*/
		$response = $this->actingAs(auth()->user())->putJson('/companies/current-company/profile', []);

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
	* Teste de atualização dos dados da empresa "atual" com usuário logado
	* que é membro da empresa, mas com parâmetros inválidos
	*/
    public function test_update_current_company_information_with_authenticated_user_and_member_of_company_but_with_invalid_update_params()
    {
		// Cria um usuário
		$user = $this->createAdminUser();

		// Faz login
		Auth::loginUsingId($user->id);

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

		/*
		* Faz a requisição para atualizar os dados da empresa "atual"
		* Usuário logado: SIM
		* Membro da empresa: SIM
		* Parametros de update válidos: SIM
		*/
		$response = $this->actingAs(auth()->user())->putJson('/companies/current-company/profile', []);

		// Verifica se o usuário não está logado
        $this->assertAuthenticated();

		// Verifica se está correta a estrutura do JSON de resposta com erro de validação
        $response->assertJsonStructure([
			'message',
            'errors' => []
		]);
		
		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();
    }
	
	/*
	* Teste de atualização dos dados da empresa "atual" com usuário logado
	* que é membro da empresa e com parâmetros inválidos
	*/
    public function test_update_current_company_information_with_authenticated_user_and_member_of_company_and_valid_update_params()
    {
		// Cria um usuário
		$user = $this->createAdminUser();

		// Faz login
		Auth::loginUsingId($user->id);

		// Cria a empresa
		$company = $this->createCompany($user->id);

		/*
		* Tenta setar a empresa criada como "current company" do usuário logado
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		$data = [
			'company_id' => $company->id,
			'user_id' => $user->id,
			'name' => $this->faker->name()
		];

		/*
		* Faz a requisição para atualizar os dados da empresa "atual"
		* Usuário logado: SIM
		* Membro da empresa: SIM
		* Parametros de update válidos: SIM
		*/
		$response = $this->actingAs(Auth::user())->putJson('/companies/current-company/profile', $data);

		// Verifica se o usuário não está logado
        $this->assertAuthenticated();

		// Verifica se a coluna "current_company_id" foi atualizada
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
