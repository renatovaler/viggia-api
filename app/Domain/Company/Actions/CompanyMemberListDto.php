<?php declare(strict_types=1);

namespace App\Domain\Company\Actions;

use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyMember;
use App\Domain\Company\Actions\CompanyMemberInformationDto;

final class CompanyMemberListDto
{
    public static function fromCollection(EloquentCollection $company): SupportCollection
    {
        return $company->map(function ($companyMember) {
			return CompanyMemberInformationDto::fromModel($companyMember);
		});
    }
}
