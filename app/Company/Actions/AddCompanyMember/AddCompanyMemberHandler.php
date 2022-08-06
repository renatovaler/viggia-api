<?php declare(strict_types=1);

namespace App\Company\Actions\AddCompanyMember;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Company\Models\Company;
use App\Company\Models\CompanyInvitation;
use App\Company\Actions\AddCompanyMember\AddCompanyMember;

final class AddCompanyMemberHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\AddCompanyMember\AddCompanyMember $command
     * @return void
     */
    public function handle(AddCompanyMember $command): void
    {
        DB::beginTransaction();

            // Pega os dados do convite
            $invite = CompanyInvitation::where('token', $command->inviteToken)->firstOrFail();

            // Pega os dados da company
            $company = Company::where('id', $invite->company_id)->firstOrFail();

            // Adiciona o usuário à equipe
            $company->onlyCompanyMembers()->attach($command->userId);

            // Pega os dados do usuário
            $user = $company->companyMemberById($command->userId);

            // Pega os dados das roles do usuário em relação à equipe
            $roles = collect($invite->roles);

            // Adiciona as permissões da company ao usuário
            $user->fresh()->addRoleToCompanyMember($roles->values()->pluck('id')->toArray(), $company->id);

            // Deleta o convite
            $invite->delete();
            
        DB::commit();
    }
}
