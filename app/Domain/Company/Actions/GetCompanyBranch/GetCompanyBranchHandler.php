<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetCompanyBranch;

use App\Domain\Company\Models\CompanyBranch;
use App\Domain\Company\Actions\CompanyBranchDto;
use App\Domain\Company\Actions\GetCompanyBranch\GetCompanyBranchCommand;

final class GetCompanyBranchHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\GetCompanyBranch\GetCompanyBranchCommand $command
     * @return \App\Domain\Company\Actions\CompanyBranchDto
     */
    public function handle(GetCompanyBranchCommand $command): CompanyBranchDto
    {
        $companyBranch = CompanyBranch::where('id', $command->id)->firstOrFail();
        return CompanyBranchDto::fromModel($companyBranch);
    }
}
