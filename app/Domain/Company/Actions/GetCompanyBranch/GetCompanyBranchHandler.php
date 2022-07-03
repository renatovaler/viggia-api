<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetCompanyBranch;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\Company\Models\CompanyBranch;
use App\Domain\Company\Actions\CompanyBranchDto;
use App\Domain\Company\Actions\GetCompanyBranch\GetCompanyBranchCommand;

final class GetCompanyBranchHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\GetCompanyBranch\GetCompanyBranchCommand $command
     * @return \App\Domain\Company\Actions\CompanyBranchDto
     */
    public function handle(GetCompanyBranchCommand $command): CompanyBranchDto
    {
        try {
            $companyBranch = CompanyBranch::where('id', $command->id)->firstOrFail();
            return CompanyBranchDto::fromModel($companyBranch);
        } catch(QueryException $e) {
			throw new Exception(__('An internal error occurred during our database search.'), 500);
        } catch(ModelNotFoundException $e) {
			throw new Exception(__('The informed company branch does not exist in our database.'), 404);
        } catch(Exception $e) {
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
