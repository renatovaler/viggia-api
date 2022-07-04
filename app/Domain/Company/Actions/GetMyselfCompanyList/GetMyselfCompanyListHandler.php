<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetMyselfCompanyList;

use Illuminate\Support\Collection;

use App\Domain\Company\Actions\CompanyListDto;

final class GetMyselfCompanyListHandler
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
