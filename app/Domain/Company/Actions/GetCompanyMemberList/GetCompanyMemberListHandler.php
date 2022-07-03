<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetCompanyMemberList;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\CompanyMemberListDto;
use App\Domain\Company\Actions\GetCompanyMemberList\GetCompanyMemberListCommand;

final class GetCompanyMemberListHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\GetCompanyMemberList\GetCompanyMemberListCommand $command
     * @return \App\Domain\Company\Actions\CompanyMemberListDto
     */
    public function handle(GetCompanyMemberListCommand $command): CompanyMemberListDto
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
