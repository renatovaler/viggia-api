<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use App\UI\Company\Http\Resources\CompanyCollection;
use App\Structure\Http\Controllers\Controller;
use App\Domain\Company\Actions\GetCurrentUserCompanyList\GetCurrentUserCompanyListCommand;

class GetCurrentUserCompanyListController extends Controller
{
    /**
     * Get list of authenticated user companies
     *
     * @return \App\UI\Company\Http\Resources\CompanyCollection
     */
    public function __invoke(): CompanyCollection
    {
        $companies = dispatch_sync(
            new GetCurrentUserCompanyListCommand()
        );
        return (new CompanyCollection($companies));
    }
}
