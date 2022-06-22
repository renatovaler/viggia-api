<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use App\Structure\Http\Controllers\Controller;
use App\UI\Company\Http\Resources\CompanyResource;
use App\Domain\Company\Actions\GetCompany\GetCompanyCommand;

class GetCurrentCompanyInformationController extends Controller
{
    /**
     * Get current user company information
     *
     * @return \App\UI\Company\Http\Resources\CompanyResource
     */
    public function __invoke(): CompanyResource
    {
        $company = dispatch_sync(
            new GetCompanyCommand( auth()->user()->current_company_id )
        );
        return (new CompanyResource($company));
    }
}
