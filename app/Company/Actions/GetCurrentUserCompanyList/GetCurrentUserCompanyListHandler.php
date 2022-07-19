<?php declare(strict_types=1);

namespace App\Company\Actions\GetCurrentUserCompanyList;

use Illuminate\Support\Collection;

use App\Company\Actions\CompanyListDto;

final class GetCurrentUserCompanyListHandler
{
    /**
     * Executa a ação
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function handle(): Collection
    {
        $companies = auth()->user()->allCompanies();
        return CompanyListDto::fromCollection($companies);
    }
}
