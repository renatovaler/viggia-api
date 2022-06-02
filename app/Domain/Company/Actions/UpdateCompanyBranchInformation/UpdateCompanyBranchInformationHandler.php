<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\UpdateCompanyBranchInformation;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\Company\Models\CompanyBranch;
use App\Domain\Company\Actions\CompanyBranchDto;
use App\Domain\Company\Actions\UpdateCompanyBranchInformation\UpdateCompanyBranchInformationCommand;

final class UpdateCompanyBranchInformationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\UpdateCompanyBranchInformation\UpdateCompanyBranchInformationCommand $command
     * @return \App\Domain\Company\Actions\CompanyBranchDto
     */
    public function handle(UpdateCompanyBranchInformationCommand $command): CompanyBranchDto
    {
        try {
            DB::beginTransaction();
                $companyBranch = CompanyBranch::where('id', $command->id)->firstOrFail();
                $companyBranch->forceFill([
                    'name' => $command->name
                ])->save();
            DB::commit();
			return CompanyBranchDto::fromModel($companyBranch);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred during our database search.'), 403);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed company branch does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred..'), 500);
        }
    }
}
