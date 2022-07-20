<?php declare(strict_types=1);

namespace App\Company\Actions\UpdateCompanyInformation;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Company\Models\Company;
use App\Company\Actions\CompanyDto;
use App\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformation;

final class UpdateCompanyInformationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformation $command
     * @return \App\Company\Actions\CompanyDto
     */
    public function handle(UpdateCompanyInformation $command): CompanyDto
    {
        try {
            DB::beginTransaction();
                $company = Company::where('id', $command->id)->firstOrFail();
                $company->forceFill([
                    'name' => $command->name
                ])->save();
            DB::commit();
			return CompanyDto::fromModel($company);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed company does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
