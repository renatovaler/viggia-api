<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\CreateCompanyBranch;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Domain\Company\Models\CompanyBranch;
use App\Domain\Company\Actions\CompanyBranchDto;
use App\Domain\Company\Actions\CreateCompanyBranch\CreateCompanyBranchCommand;

final class CreateCompanyBranchHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\CreateCompanyBranch\CreateCompanyBranchCommand $command
     * @return CompanyBranchDto
     */
    public function handle(CreateCompanyBranchCommand $command): CompanyBranchDto
    {
        try {
            DB::beginTransaction();
				$companyBranch = CompanyBranch::create([
					'owner_company_id' => $command->ownerCompanyId,
					'name' => $command->name
				]);
            DB::commit();
			return CompanyBranchDto::fromModel($companyBranch);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 403);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
