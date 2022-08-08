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

class AcceptInviteWithNonExistingUserTest extends TestCase
{
	use WithFaker;
    use RefreshDatabase;

	protected function generateUniqueEmail()
	{
		// Gera um e-mail a ser convidado como membro
		$email = $this->faker->unique()->safeEmail();
		return (
			(new User())->where('email', $email)->exists() ?
			$this->generateUniqueEmail() :
			$email
		);
	}

	/**
	 * Teste - aceitar convite com usuário não cadastrado, convite válido e parâmetros válidos
	 * Usuário já cadastrado: NÃO
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	 * Convite expirado: NÃO
	*/
	public function test_accept_invite_to_company_with_non_existing_user()
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
			'name' => $this->faker->name(),
            'password' => 'password',
            'password_confirmation' => 'password',
		];

		// Faz o request para aceitar o convite
        $response = $this->postJson('/companies/company-invitations/accept', $data);

		// Verifica se o usuário foi criado com o e-mail do convite
		$this->assertDatabaseHas('users', ['email' => $invitation->email]);

		// Verifica se o convite foi deletado
		$this->assertDatabaseMissing('company_invitations', [
			'id' => $invitation->id,
			'token' => $invitation->token
		]);

		// Verifica se o usuário foi adicionado à equipe
		$this->assertTrue(
			$company->fresh()->hasOnlyCompanyMemberWithEmail($invitation->email)
		);

		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();
	}
}
