<?php declare(strict_types=1);

namespace App\Company\Actions\GetCompany;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Company\Models\Company;
use App\Company\Actions\CompanyDto;
use App\Company\Actions\GetCompany\GetCompany;

final class GetCompanyHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\GetCompany\GetCompany $command
     * @return CompanyDto
     */
    public function handle(GetCompany $command): CompanyDto
    {
        $company = Company::where('id', $command->id)->firstOrFail();
        return CompanyDto::fromModel($company);
    }
}
