<?php declare(strict_types=1);

namespace App\Company\Actions\AddCompanyBranchMember;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Company\Models\CompanyBranch;
use App\Company\Actions\AddCompanyBranchMember\AddCompanyBranchMember;

final class AddCompanyBranchMemberHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\AddCompanyBranchMember\AddCompanyBranchMember $command
     * @return void
     */
    public function handle(AddCompanyBranchMember $command): void
    {
        try {
            DB::beginTransaction();
				$companyBranch = CompanyBranch::where('id', $command->companyBranchId)->firstOrFail();
				$companyBranch->onlyCompanyBranchMembers()->attach($command->userId);
            DB::commit();
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed company branch does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
