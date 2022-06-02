<?php declare(strict_types=1);

namespace App\Domain\User\Actions\GetCompany;

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
        $company = Company::where('id', $command->id)->firstOrFail();
        return CompanyDto::fromModel($company);
    }
}
