<?php declare(strict_types=1);

namespace App\Company\Actions\InviteNewCompanyMember;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Company\Mail\CompanyInvitation;
use App\Company\Models\CompanyInvitation as CompanyInvitationModel;
use App\Company\Actions\InviteNewCompanyMember\InviteNewCompanyMember;

final class InviteNewCompanyMemberHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\InviteNewCompanyMember\InviteNewCompanyMember $command
     * @return void
     */
    public function handle(InviteNewCompanyMember $command): void
    {
        try {
            DB::beginTransaction();

                // Cria o convite
                $invitation = CompanyInvitationModel::create([
                    'company_id' => $command->companyId,
                    'email' => $command->email,
                    'roles' => $command->roles,
                    'token' => (string) Str::orderedUuid(),
                    'expires_in' => Carbon::now()->addHours(48)
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
