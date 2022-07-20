<?php declare(strict_types=1);

namespace App\Company\Http\Controllers;

use App\Structure\Http\Controllers\Controller;
use App\Company\Http\Resources\CompanyResource;
use App\Company\Actions\GetCompany\GetCompany;

class GetCurrentCompanyInformationController extends Controller
{
    /**
     * Get current user company information
     *
     * @return \App\Company\Http\Resources\CompanyResource
     * 
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(): CompanyResource
    {
        $this->authorize('view', auth()->user()->currentCompany);

        $company = dispatch_sync(
            new GetCompany( auth()->user()->current_company_id )
        );
        return (new CompanyResource($company));
    }
}
