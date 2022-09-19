<?php

namespace Tests\Company\CompanyMember;

use App\User\Models\User;
use App\Company\Models\Company;

use App\Role\Models\Role;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCompanyMemberList extends TestCase
{
	use WithFaker;
    use RefreshDatabase;

	/**
	 * Teste - Lista membros da empresa com usuário logado e autorizado e parâmetros de request válidos
	 * Usuário logado: SIM
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	*/
	public function test_get_company_member_list_with_logged_and_autorized_user_and_valid_params()
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

		// Gera o array de roles dos membros da equipe
		$roles = [
			(new Role())->where('name', 'company_member')->first()->id
		];

		// Cria 5 usuários a serem convidados para a empresa
		$memberUser1 = $this->createCommonUser();
		$memberUser2 = $this->createCommonUser();
		$memberUser3 = $this->createCommonUser();
		$memberUser4 = $this->createCommonUser();
		$memberUser5 = $this->createCommonUser();

		// Adiciona os usuários como membros da empresa
		$company->onlyCompanyMembers()->attach([
			$memberUser1->id,
			$memberUser2->id,
			$memberUser3->id,
			$memberUser4->id,
			$memberUser5->id,
		]);

		// Adiciona as permissões da company aos usuários
		$memberUser1->fresh()->addRoleToCompanyMember($roles, $company->id);
		$memberUser2->fresh()->addRoleToCompanyMember($roles, $company->id);
		$memberUser3->fresh()->addRoleToCompanyMember($roles, $company->id);
		$memberUser4->fresh()->addRoleToCompanyMember($roles, $company->id);
		$memberUser5->fresh()->addRoleToCompanyMember($roles, $company->id);

		/*
		* Seta a empresa como sendo a "current company" dos novos membros
		*/
		$memberUser1->fresh()->switchCompany($company);
		$memberUser2->fresh()->switchCompany($company);
		$memberUser3->fresh()->switchCompany($company);
		$memberUser4->fresh()->switchCompany($company);
		$memberUser5->fresh()->switchCompany($company);

		// Faz login do memberUser1
		Auth::loginUsingId($memberUser1->id);
		
		// Verifica se o memberUser1 está logado
		$this->assertAuthenticated();

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

		// Cria o convite já expirado
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
