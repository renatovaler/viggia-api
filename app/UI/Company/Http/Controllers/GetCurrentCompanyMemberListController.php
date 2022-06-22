<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use App\Structure\Http\Controllers\Controller;
use App\UI\Company\Http\Resources\CompanyMemberCollection;
use App\Domain\Company\Actions\GetCompanyMemberList\GetCompanyMemberListCommand;

class GetCurrentCompanyMemberListController extends Controller
{
    /**
     * Get current user company information
     *
     * @return \App\UI\Company\Http\Resources\CompanyResource
     */
    public function __invoke(): CompanyResource
    {
        $companyMembers = dispatch_sync(
            new GetCompanyMemberListCommand( auth()->user()->current_company_id )
        );
        return (new CompanyMemberCollection($companyMembers));
    }
}
