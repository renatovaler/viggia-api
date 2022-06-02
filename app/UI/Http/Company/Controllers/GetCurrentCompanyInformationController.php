<?php declare(strict_types=1);

namespace App\UI\Http\Company\Controllers;

use App\Domain\Company\Actions\GetCompany\GetCompanyCommand;
use App\UI\Http\Company\Resources\CompanyResource;
use App\Structure\Http\Controllers\Controller;

class GetCurrentCompanyInformationController extends Controller
{
    /**
     * Get current user company information
     *
     * @return \App\UI\Http\Company\Resources\CompanyResource
     */
    public function __invoke(): CompanyResource
    {
        $company = dispatch_sync(
            new GetCompanyCommand( auth()->user()->current_company_id )
        );
        return (new CompanyResource($company));
    }
}
