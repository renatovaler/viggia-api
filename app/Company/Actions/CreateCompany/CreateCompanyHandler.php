<?php declare(strict_types=1);

namespace App\Company\Actions\CreateCompany;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Company\Models\Company;
use App\Company\Actions\CompanyDto;
use App\Company\Actions\CreateCompany\CreateCompany;

final class CreateCompanyHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\CreateCompany\CreateCompany $command
     * @return CompanyDto
     */
    public function handle(CreateCompany $command): CompanyDto
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
