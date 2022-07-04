<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetCompanyList;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\CompanyListDto;

final class GetCompanyListHandler
{
    /**
     * Executa a ação
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function handle(): Collection
    {
        $companies = Company::all();
        return CompanyListDto::fromCollection($companies);
    }
}
