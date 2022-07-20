<?php declare(strict_types=1);

namespace App\Company\Actions\GetCompanyMemberList;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Company\Models\Company;
use App\Company\Actions\CompanyMemberListDto;
use App\Company\Actions\GetCompanyMemberList\GetCompanyMemberList;

final class GetCompanyMemberListHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\GetCompanyMemberList\GetCompanyMemberList $command
     * @return \App\Company\Actions\CompanyMemberListDto
     */
    public function handle(GetCompanyMemberList $command): CompanyMemberListDto
    {
        try {
            $company = Company::where('id', $command->companyId)->firstOrFail();
            return CompanyMemberListDto::fromCollection($company->companyMembersAndOwner);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 500);
        } catch(ModelNotFoundException $e) {
            throw new Exception(__('The informed company does not exist in our database.'), 404);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
