<?php declare(strict_types=1);

namespace App\Company\Actions\InviteCompanyMember;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Company\Mail\CompanyInvitation;
use App\Company\Models\CompanyInvitation as CompanyInvitationModel;
use App\Company\Actions\InviteCompanyMember\InviteCompanyMemberCommand;

final class InviteCompanyMemberHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\InviteCompanyMember\InviteCompanyMemberCommand $command
     * @return void
     */
    public function handle(InviteCompanyMemberCommand $command): void
    {
        try {
            DB::beginTransaction();

                // Cria o convite
                $invitation = CompanyInvitationModel::create([
                    'company_id' => $command->companyId,
                    'email' => $command->email,
                    'roles' => $command->roles,
                ]);

            DB::commit();
            
            // Envia o e-mail
            Mail::to($command->email)->send(new CompanyInvitation($invitation));

		}   catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
