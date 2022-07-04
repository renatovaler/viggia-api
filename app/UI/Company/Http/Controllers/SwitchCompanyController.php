<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\UI\Company\Http\Resources\CompanyResource;
use App\UI\Company\Http\Requests\SwitchCompanyRequest;
use App\Domain\Company\Actions\SwitchCompany\SwitchCompanyCommand;

class SwitchCompanyController extends Controller
{
    /**
     * Update current user company information
     *
     * @param App\UI\Company\Http\Requests\SwitchCompanyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SwitchCompanyRequest $request): JsonResponse
    {
        $companyUpdated = dispatch_sync(new SwitchCompanyCommand(
            (int) $request->input('company_id')
        ));
        return (new CompanyResource($companyUpdated))->response($request);
    }
}
