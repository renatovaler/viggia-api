<?php declare(strict_types=1);

namespace App\Company\Actions;

use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Company\Actions\CompanyDto;

final class CompanyListDto
{
    public static function fromCollection(EloquentCollection $companies): SupportCollection
    {
        return $companies->map(function ($company) {
			return CompanyDto::fromModel($company);
		});
    }
}
