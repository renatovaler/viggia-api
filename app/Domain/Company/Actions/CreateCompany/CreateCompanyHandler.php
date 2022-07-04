<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\CreateCompany;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\CompanyDto;
use App\Domain\Company\Actions\CreateCompany\CreateCompanyCommand;

final class CreateCompanyHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\CreateCompany\CreateCompanyCommand $command
     * @return CompanyDto
     */
    public function handle(CreateCompanyCommand $command): CompanyDto
    {
        try {
            DB::beginTransaction();
				$company = Company::create([
					'user_id' => $command->ownerUserId,
					'name' => $command->name
				]);
            DB::commit();
			return CompanyDto::fromModel($company);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
