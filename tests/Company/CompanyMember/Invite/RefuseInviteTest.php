<?php

namespace Tests\Company\CompanyMember\Invite;

use App\User\Models\User;
use App\Company\Models\Company;

use App\Role\Models\Role;
use App\Company\Models\CompanyInvitation;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RefuseInviteTest extends TestCase
{
	use WithFaker;
    use RefreshDatabase;

	/**
	 * Teste - Recusa convite com usuário já existente, convite válido e parâmetros válidos
	 * Usuário já cadastrado: SIM
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	 * Convite expirado: NÃO
	*/
	public function test_refuse_invite_to_company_with_existing_user_and_valid_invite_and_valid_params()
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
		$invitation = CompanyInvitation::create([
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

		// Faz o request para recusar o convite
        $response = $this->deleteJson('/companies/company-invitations/refuse/' . $invitation->id);

		// Verifica se o convite foi deletado
		$this->assertDatabaseMissing('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
	}

	/**
	 * Teste - Recusa convite com usuário já existente, mas com convite expirado
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
		$invitation = CompanyInvitation::create([
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

		// Faz o request para recusar o convite
        $response = $this->deleteJson('/companies/company-invitations/refuse/' . $invitation->id);
        
		// Confirma se o convite NÃO foi foi deletado
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'message',
			'errors' => [
				'invite_expired'
			]
		]);

		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();
	}


	/**
	 * Teste - Recusa convite com usuário NÃO cadastrado, convite válido e parâmetros válidos
	 * Usuário já cadastrado: NÃO
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	 * Convite expirado: NÃO
	*/
	public function test_refuse_invite_to_company_with_non_existing_user_and_valid_invite_and_valid_params()
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

		// Cria um e-mail do usuário a ser convidado
		$email = $this->faker->unique()->safeEmail();

		// Cria o convite
		$invitation = CompanyInvitation::create([
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

		// Faz o request para recusar o convite
        $response = $this->deleteJson('/companies/company-invitations/refuse/' . $invitation->id);

		// Verifica se o convite foi deletado
		$this->assertDatabaseMissing('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Confirma que o usuário novo não foi cadastrado
		$this->assertDatabaseMissing('users', [
			'email' => $email
		]);

		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
	}

	/**
	 * Teste - Recusa convite com usuário NÃO cadastrado e com convite expirado
	 * Usuário já cadastrado: NÃO
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	 * Convite expirado: SIM
	*/
	public function test_verify_invite_to_company_with_non_existing_user_and_expired_invite()
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

		// Cria um e-mail do usuário a ser convidado
		$email = $this->faker->unique()->safeEmail();

		// Cria o convite com token já expirado
		$invitation = CompanyInvitation::create([
			'company_id' => $company->id,
			'email' => $email,
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

		// Faz o request para recusar o convite
        $response = $this->deleteJson('/companies/company-invitations/refuse/' . $invitation->id);
        
		// Confirma se o convite NÃO foi foi deletado
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Confirma que o usuário novo não foi cadastrado
		$this->assertDatabaseMissing('users', [
			'email' => $email
		]);

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'message',
			'errors' => [
				'invite_expired'
			]
		]);

		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();
	}

	/**
	 * Teste - Verifica convite com usuário cadastrado, mas com parâmetros inválidos
	 * Como o convite "enviado" não existe, fica prejudicada a informação de expirado
	 * Usuário já cadastrado: SIM
	 * Autorizado: SIM
	 * Parâmetros válidos: NÃO
	 * Convite expirado: PREJUDICADO
	*/
	public function test_refuse_invite_to_company_with_existing_user_and_invalid_params()
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
		$invitation = CompanyInvitation::create([
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

		// Faz o request para recusar o convite informando parâmetro inválido
        $response = $this->deleteJson('/companies/company-invitations/refuse/99999999999999');
        
		// Verifica se o convite ainda existe
		$this->assertDatabaseHas('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);
		
		// Verifica se o código de resposta HTTP está correto (404)
		$response->assertNotFound();
	}
}
