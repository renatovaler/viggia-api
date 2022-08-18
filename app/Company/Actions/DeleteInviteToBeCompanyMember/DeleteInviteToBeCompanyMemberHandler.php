<?php declare(strict_types=1);

namespace App\Company\Actions\DeleteInviteToBeCompanyMember;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Company\Models\CompanyInvitation;
use App\Company\Actions\DeleteInviteToBeCompanyMember\DeleteInviteToBeCompanyMember;

final class DeleteInviteToBeCompanyMemberHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\DeleteInviteToBeCompanyMember\DeleteInviteToBeCompanyMember $command
     * @return void
     */
    public function handle(DeleteInviteToBeCompanyMember $command): void
    {
        try {
            DB::beginTransaction();
                (new CompanyInvitation())->removeInvite($command->inviteId);
            DB::commit();
		}   catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
