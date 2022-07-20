<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\CompanyBranch;

use App\Structure\Http\Controllers\Controller;

use App\Company\Http\Resources\CompanyBranchResource;
use App\Company\Http\Requests\CreateCompanyBranchRequest;

use App\Company\Actions\CreateCompanyBranch\CreateCompanyBranch;

class CreateCompanyBranchController extends Controller
{
    /**
     * Update current user company information
     *
     * @param App\Company\Http\Requests\CreateCompanyBranchRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreateCompanyBranchRequest $request): JsonResponse
    {
        $companyBranch = dispatch_sync(new CreateCompanyBranch(
            $request->input('company_id'),
            $request->input('name')
        ));
        return (new CompanyBranchResource($companyBranch))->response($request);
    }
}
