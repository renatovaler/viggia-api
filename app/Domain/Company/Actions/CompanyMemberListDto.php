<?php declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyMember;
use App\Domain\Company\Actions\CompanyMemberInformationDto;

final class CompanyMemberListDto
{
    public static function fromCollection(Company $company): self
    {
        return $company->map(function ($companyMember) {
			return CompanyMemberInformationDto::fromModel($companyMember);
		});
    }
}
