<?php declare(strict_types=1);

namespace App\UI\Http\Company\Controllers;

use App\UI\Http\Company\Resources\CompanyResource;
use App\Structure\Http\Controllers\Controller;
use App\UI\Http\Company\Requests\UpdateCompanyInformationRequest;

use App\Domain\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationCommand;
use Illuminate\Http\JsonResponse;

class UpdateCurrentCompanyInformationController extends Controller
{
    /**
     * Update current user company information
     *
     * @param App\UI\Http\Company\Requests\UpdateCompanyInformationRequest $request
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
