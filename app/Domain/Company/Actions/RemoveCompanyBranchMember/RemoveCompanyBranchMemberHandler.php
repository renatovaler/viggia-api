<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\RemoveCompanyBranchMember;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\Company\Models\CompanyBranch;
use App\Domain\Company\Actions\RemoveCompanyBranchMember\RemoveCompanyBranchMemberCommand;

final class RemoveCompanyBranchMemberHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\RemoveCompanyBranchMember\RemoveCompanyBranchMemberCommand $command
     * @return void
     */
    public function handle(RemoveCompanyBranchMemberCommand $command): void
    {
        try {
            DB::beginTransaction();
				$companyBranch = CompanyBranch::where('id', $command->companyBranchId)->firstOrFail();
				$companyBranch->onlyCompanyBranchMembers()->detach($command->userId);
            DB::commit();
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while executing the action on the database.'), 403);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed company branch does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred..'), 500);
        }
    }
}
