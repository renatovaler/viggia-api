<?php declare(strict_types=1);

namespace App\Company\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\Company\Http\Resources\CompanyResource;
use App\Company\Http\Requests\SwitchCompanyRequest;

use App\Company\Models\Company;
use App\Company\Actions\SwitchCompany\SwitchCompanyCommand;

class SwitchCompanyController extends Controller
{
    /**
     * Update current user company information
     *
     * @param App\Company\Http\Requests\SwitchCompanyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SwitchCompanyRequest $request): JsonResponse
    {
        $company = Company::where('id', (int) $request->input('company_id') )->first();
        
        $this->authorize('switchCompany', $company);

        $companyUpdated = dispatch_sync(new SwitchCompanyCommand(
            (int) $request->input('company_id')
        ));
        return (new CompanyResource($companyUpdated))->response($request);
    }
}
