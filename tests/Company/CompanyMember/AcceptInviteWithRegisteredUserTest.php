<?php

namespace Tests\Company\CompanyMember;

use App\User\Models\User;
use App\Company\Models\Company;

use App\Role\Models\Role;
use App\Company\Mail\CompanyInvitation as CompanyInvitationInvite;
use App\Company\Models\CompanyInvitation as CompanyInvitationModel;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcceptInviteWithRegisteredUserTest extends TestCase
{
	use WithFaker;
    use RefreshDatabase;

	/**
	 * Teste - aceitar convite com usuário já existente, convite válido e parâmetros válidos
	 * Usuário já cadastrado: SIM
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	 * Convite expirado: NÃO
	*/
	public function test_accept_invite_to_company_with_existing_user()
	{
		// Cria um usuário Owner da empresa
		$ownerUser = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($ownerUser->id);

		// Verifica se a coluna "current_company_id" do usuário logado = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);
		
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Cria a empresa
		$company = $this->createCompany($ownerUser->id);

		/*
		* Tenta setar a empresa criada como "current company" do usuário memberUser
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" do usuário logado foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Faz logout do Owner
		Auth::logout();

		// Verifica se o Owner foi mesmo deslogado
        $this->assertGuest();

		// Cria um usuário a ser convidado para a empresa
		$memberUser = $this->createCommonUser();

		// Faz login do memberUser
		Auth::loginUsingId($memberUser->id);
		
		// Verifica se o memberUser está logado
		$this->assertAuthenticated();

		// Cria o convite
		$invitation = CompanyInvitationModel::create([
			'company_id' => $company->id,
			'email' => $memberUser->email,
			'roles' => [
				['id' => $systemRole = ( (new Role())->where('name', 'common_user')->first() )->id],
				['id' => $companyRole = ( (new Role())->where('name', 'company_member')->first() )->id]
			],
			'token' => (string) Str::orderedUuid(),
			'expires_in' => Carbon::now()->addHours(48)
		]);

		// Monta o array com os parâmetros do request
		$data = [
			'token' => $invitation->token,
			'email' => $invitation->email,
			'name' => $this->faker->name()
		];

		// Faz o request para aceitar o convite
        $response = $this->actingAs(Auth::user())->postJson('/companies/company-invitations/accept', $data);

		// Verifica se o usuário foi adicionado à equipe
		$this->assertTrue(
			$company->fresh()->hasOnlyCompanyMember($memberUser->id)
		);

		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();

		// Verifica se o convite foi deletado
		$this->assertDatabaseMissing('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);
	}
	

	/**
	 * Teste - aceitar convite com usuário existente, convite válido, mas parâmetros de request inválidos
	 * Usuário já cadastrado: SIM
	 * Autorizado: SIM
	 * Parâmetros válidos: NÃO
	 * Convite expirado: NÃO
	*/
	public function test_accept_invite_to_company_with_existing_user_valid_invite_but_invalid_params()
	{
		// Cria um usuário Owner da empresa
		$ownerUser = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($ownerUser->id);

		// Verifica se a coluna "current_company_id" do usuário logado = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);
		
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Cria a empresa
		$company = $this->createCompany($ownerUser->id);

		/*
		* Tenta setar a empresa criada como "current company" do usuário memberUser
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" do usuário logado foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Faz logout do Owner
		Auth::logout();

		// Verifica se o Owner foi mesmo deslogado
        $this->assertGuest();

		// Cria um usuário a ser convidado para a empresa
		$memberUser = $this->createCommonUser();

		// Faz login do memberUser
		Auth::loginUsingId($memberUser->id);
		
		// Verifica se o memberUser está logado
		$this->assertAuthenticated();

		// Cria o convite
		$invitation = CompanyInvitationModel::create([
			'company_id' => $company->id,
			'email' => $memberUser->email,
			'roles' => [
				['id' => $systemRole = ( (new Role())->where('name', 'common_user')->first() )->id],
				['id' => $companyRole = ( (new Role())->where('name', 'company_member')->first() )->id]
			],
			'token' => (string) Str::orderedUuid(),
			'expires_in' => Carbon::now()->addHours(48)
		]);

		// Faz o request para aceitar o convite, mas com parâmetros inválidos
        $response = $this->actingAs(Auth::user())->postJson('/companies/company-invitations/accept', []);

		// Confirma que o usuário NÃO foi adicionado à equipe
		$this->assertFalse(
			$company->fresh()->hasOnlyCompanyMember($memberUser->id)
		);

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'message',
			'errors' => []
		]);

		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();

		// Confirma que o convite NÃO foi deletado
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);
	}

	/**
	 * Teste - aceitar convite com usuário existente, mas com convite expirado
	 * Usuário já cadastrado: SIM
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	 * Convite expirado: SIM
	*/
	public function test_accept_invite_to_company_with_existing_user_but_expired_invite()
	{
		// Cria um usuário Owner da empresa
		$ownerUser = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($ownerUser->id);

		// Verifica se a coluna "current_company_id" do usuário logado = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);
		
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Cria a empresa
		$company = $this->createCompany($ownerUser->id);

		/*
		* Tenta setar a empresa criada como "current company" do usuário memberUser
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" do usuário logado foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Faz logout do Owner
		Auth::logout();

		// Verifica se o Owner foi mesmo deslogado
        $this->assertGuest();

		// Cria um usuário a ser convidado para a empresa
		$memberUser = $this->createCommonUser();

		// Faz login do memberUser
		Auth::loginUsingId($memberUser->id);
		
		// Verifica se o memberUser está logado
		$this->assertAuthenticated();

		// Cria o convite
		$invitation = CompanyInvitationModel::create([
			'company_id' => $company->id,
			'email' => $memberUser->email,
			'roles' => [
				['id' => $systemRole = ( (new Role())->where('name', 'common_user')->first() )->id],
				['id' => $companyRole = ( (new Role())->where('name', 'company_member')->first() )->id]
			],
			'token' => (string) Str::orderedUuid(),
			'expires_in' => Carbon::now()->subHours(48) // Token expirado
		]);

		// Monta o array com os parâmetros do request
		$data = [
			'token' => $invitation->token,
			'email' => $invitation->email,
			'name' => $this->faker->name()
		];

		// Faz o request para aceitar o convite já expirado
        $response = $this->actingAs(Auth::user())->postJson('/companies/company-invitations/accept', $data);

		// Confirma que o usuário NÃO foi adicionado à equipe
		$this->assertFalse(
			$company->fresh()->hasOnlyCompanyMember($memberUser->id)
		);

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'message',
			'errors' => []
		]);

		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();

		// Confirma que o convite NÃO foi deletado
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);
	}
}
