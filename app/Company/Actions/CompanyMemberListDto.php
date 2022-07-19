<?php declare(strict_types=1);

namespace App\Company\Actions;

use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

use App\Company\Models\Company;
use App\Company\Models\CompanyMember;
use App\Company\Actions\CompanyMemberInformationDto;

final class CompanyMemberListDto
{
    public static function fromCollection(EloquentCollection $company): SupportCollection
    {
        return $company->map(function ($companyMember) {
			return CompanyMemberInformationDto::fromModel($companyMember);
		});
    }
}
