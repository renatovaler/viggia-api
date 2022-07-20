<?php declare(strict_types=1);

namespace App\Company\Actions\CreateCompanyBranch;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Company\Models\CompanyBranch;
use App\Company\Actions\CompanyBranchDto;
use App\Company\Actions\CreateCompanyBranch\CreateCompanyBranch;

final class CreateCompanyBranchHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\CreateCompanyBranch\CreateCompanyBranch $command
     * @return CompanyBranchDto
     */
    public function handle(CreateCompanyBranch $command): CompanyBranchDto
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
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
