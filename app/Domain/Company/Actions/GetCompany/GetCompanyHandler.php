<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetCompany;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\CompanyDto;
use App\Domain\Company\Actions\GetCompany\GetCompanyCommand;

final class GetCompanyHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\GetCompany\GetCompanyCommand $command
     * @return \App\Domain\Company\Actions\CompanyDto
     */
    public function handle(GetCompanyCommand $command): CompanyDto
    {
        try {
            $company = Company::where('id', $command->id)->firstOrFail();
            return CompanyDto::fromModel($company);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 500);
        } catch(ModelNotFoundException $e) {
            throw new Exception(__('The informed company does not exist in our database.'), 404);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
