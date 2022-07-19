<?php

namespace Tests\Company\CompanyMember;

use App\User\Models\User;
use App\Company\Models\Company;

use App\Company\Mail\CompanyInvitation as CompanyInvitationInvite;
use App\Company\Models\CompanyInvitation as CompanyInvitationModel;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InviteCompanyMemberTest extends TestCase
{
    use RefreshDatabase;
	use WithFaker;
	
   
    public function test_company_members_can_be_invited_to_company()
    {
        // Instancia o e-mail fake
        Mail::fake();

		// Cria um usuário
		$user = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($user->id);

		// Verifica se a coluna "current_company_id" do usuário logado = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);
		
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Cria a empresa
		$company = $this->createCompany($user->id);

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

        // Cria o convite
        $invitation = CompanyInvitationModel::create([
            'company_id' => $company->id,
            'email' => 'renato.valer@hotmail.com',
            'roles' => collect(config('roles.default_user_company_roles'))->toJson(),
        ]);
            
        // Envia o e-mail
        Mail::to('renato.valer@hotmail.com')->send(new CompanyInvitationInvite($invitation));

        Mail::assertSent(CompanyInvitationInvite::class);

        $this->assertCount(1, Auth::user()->currentCompany->fresh()->companyInvitations);
    }
}
