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

class VerifyInviteTest extends TestCase
{
	use WithFaker;
    use RefreshDatabase;

	/**
	 * Teste - Verifica convite com usuário já existente, convite válido e parâmetros válidos
	 * Usuário já cadastrado: SIM
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	 * Convite expirado: NÃO
	*/
	public function test_verify_invite_to_company_with_existing_user_and_valid_invite_and_valid_params()
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
				(new Role())->where('name', 'company_member')->first()->id
			],
			'token' => (string) Str::orderedUuid(),
			'expires_in' => Carbon::now()->addHours(48)
		]);

		// Verifica se o convite foi criado
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Faz o request para verificar o convite
        $response = $this->getJson('/companies/company-invitations/' . $invitation->token);

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
				'id',
				'token',
				'company_id',
				'user' => [
					'id',
					'email',
					'name'
				],
				'roles',
				'expired',
				'expires_in'
			]
		]);

		// Verifica se o valor do campo "expired" é false, indicando que o token NÃO expirou
		$response->assertJsonPath('data.expired', false);

		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
	}

	/**
	 * Teste - verificar convite com usuário existente, mas com convite expirado
	 * Usuário já cadastrado: SIM
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	 * Convite expirado: SIM
	*/
	public function test_verify_invite_to_company_with_existing_user_but_expired_invite()
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
				(new Role())->where('name', 'company_member')->first()->id
			],
			'token' => (string) Str::orderedUuid(),
			'expires_in' => Carbon::now()->subHours(48) // Token expirado
		]);

		// Verifica se o convite foi criado
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Faz o request para verificar o convite
        $response = $this->getJson('/companies/company-invitations/' . $invitation->token);

		// Verifica se o convite ainda existe
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
				'id',
				'token',
				'company_id',
				'user' => [
					'id',
					'email',
					'name'
				],
				'roles',
				'expired',
				'expires_in'
			]
		]);

		// Verifica se o valor do campo "expired" é true, indicando que o token está sim expirado
		$response->assertJsonPath('data.expired', true);

		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
	}

	/**
	 * Teste - Verifica convite com usuário NÃO CADASTRADO, convite válido e parâmetros válidos
	 * Usuário já cadastrado: NÃO
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	 * Convite expirado: NÃO
	*/
	public function test_verify_invite_to_company_with_non_existing_user_and_valid_invite_and_valid_params()
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

		// Gera um e-mail a para ser convidado como membro da equipe
		$email = $this->generateUniqueEmail();

		// Confirma que esse usuário novo não existe
		$this->assertDatabaseMissing('users', [
			'email' => $email
		]);

		// Cria o convite
		$invitation = CompanyInvitationModel::create([
			'company_id' => $company->id,
			'email' => $email,
			'roles' => [
				(new Role())->where('name', 'company_member')->first()->id
			],
			'token' => (string) Str::orderedUuid(),
			'expires_in' => Carbon::now()->addHours(48)
		]);

		// Verifica se o convite foi criado
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Faz o request para verificar o convite
        $response = $this->getJson('/companies/company-invitations/' . $invitation->token);

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
				'id',
				'token',
				'company_id',
				'roles',
				'expired',
				'expires_in'
			]
		]);

		// Verifica se o valor do campo "expired" é false, indicando que o token NÃO expirou
		$response->assertJsonPath('data.expired', false);

		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
	}

	/**
	 * Teste - Verifica convite, com convite válido, mas parâmetros de request inválidos
	 * Não há necessidade de fazer 2 testes (um com usuário cadastrado e outro não), pois a resposta ao request é igual
	 * Usuário já cadastrado: INDIFERENTE
	 * Autorizado: PREJUDICADO
	 * Parâmetros válidos: NÃO
	 * Convite expirado: NÃO
	*/
	public function test_verify_invite_to_company_with_valid_invite_but_invalid_params()
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
				(new Role())->where('name', 'company_member')->first()->id
			],
			'token' => (string) Str::orderedUuid(),
			'expires_in' => Carbon::now()->addHours(48)
		]);

		// Verifica se o convite foi criado
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Faz o request para verificar o convite
        $response = $this->getJson('/companies/company-invitations/XXXXX');
        
		// Verifica se o convite ainda existe
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Verifica se o código de resposta HTTP está correto (404)
		$response->assertNotFound();
	}
}
