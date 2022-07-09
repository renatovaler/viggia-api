<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers\CompanyBranch;

use App\Structure\Http\Controllers\Controller;

use App\UI\Company\Http\Resources\CompanyBranchResource;
use App\UI\Company\Http\Requests\CreateCompanyBranchRequest;

use App\Domain\Company\Actions\CreateCompanyBranch\CreateCompanyBranchCommand;

class CreateCompanyBranchController extends Controller
{
    /**
     * Update current user company information
     *
     * @param App\UI\Company\Http\Requests\CreateCompanyBranchRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreateCompanyBranchRequest $request): JsonResponse
    {
        $companyBranch = dispatch_sync(new CreateCompanyBranchCommand(
            $request->input('company_id'),
            $request->input('name')
        ));
        return (new CompanyBranchResource($companyBranch))->response($request);
    }
}
