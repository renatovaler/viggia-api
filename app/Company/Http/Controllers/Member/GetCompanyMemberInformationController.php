<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member;

use App\Structure\Http\Controllers\Controller;
use App\Company\Http\Resources\CompanyMemberResource;
use App\Company\Actions\GetCompanyMemberInformation\GetCompanyMemberInformationCommand;

class GetCompanyMemberInformationController extends Controller
{
    /**
     * Get company member information by companyMemberId
     *
     * @param  int $companyMemberId
     *
     * @return \App\Company\Http\Resources\CompanyMemberResource
     */
    public function __invoke(int $companyMemberId): CompanyMemberResource
    {
        $companyMember = dispatch_sync(
            new GetCompanyMemberInformationCommand($companyMemberId)
        );
        return (new CompanyMemberResource($companyMember));
    }
}
