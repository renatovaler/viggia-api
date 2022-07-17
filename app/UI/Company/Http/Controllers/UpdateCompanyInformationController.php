<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\UI\Company\Http\Resources\CompanyResource;
use App\UI\Company\Http\Requests\UpdateCompanyInformationRequest;
use App\Domain\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformationCommand;

class UpdateCompanyInformationController extends Controller
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
        $this->authorize('update', auth()->user()->currentCompany);

        $companyUpdated = dispatch_sync(new UpdateCompanyInformationCommand(
            $request->input('company_id'),
            $request->input('name')
        ));
        return (new CompanyResource($companyUpdated))->response($request);
    }
}
