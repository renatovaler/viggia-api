<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\UI\Company\Http\Resources\CompanyResource;
use App\UI\Company\Http\Requests\UpdateCompanyInformationRequest;
use App\Domain\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationCommand;

class UpdateCurrentCompanyInformationController extends Controller
{
    /**
     * Update current user company information
     *
     * @param App\UI\Company\Http\Requests\UpdateCompanyInformationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateCompanyInformationRequest $request): JsonResponse
    {
        $companyUpdated = dispatch_sync(new UpdateCompanyInformationCommand(
            auth()->user()->current_company_id,
            $request->input('name')
        ));
        return (new CompanyResource($companyUpdated))->response($request);
    }
}
