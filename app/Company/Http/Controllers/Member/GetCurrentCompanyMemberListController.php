<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member;

use App\Structure\Http\Controllers\Controller;
use App\Company\Http\Resources\CompanyMemberCollection;
use App\Company\Actions\GetCompanyMemberList\GetCompanyMemberList;

class GetCurrentCompanyMemberListController extends Controller
{
    /**
     * Get current user company information
     *
     * @return \App\Company\Http\Resources\CompanyResource
     */
    public function __invoke(): CompanyResource
    {
        $companyMembers = dispatch_sync(
            new GetCompanyMemberList( auth()->user()->current_company_id )
        );
        return (new CompanyMemberCollection($companyMembers));
    }
}
