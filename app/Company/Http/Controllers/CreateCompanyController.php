<?php declare(strict_types=1);

namespace App\Company\Http\Controllers;

use App\Structure\Http\Controllers\Controller;
use App\Company\Http\Resources\CompanyResource;
use App\Company\Http\Requests\CreateCompanyRequest;

use App\Company\Models\Company;
use App\Company\Actions\CreateCompany\CreateCompany;

use Illuminate\Http\JsonResponse;

class CreateCompanyController extends Controller
{
    /**
     * Create new company for current user
     *
     * @param App\Company\Http\Requests\CreateCompanyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
    *
    * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(CreateCompanyRequest $request): JsonResponse
    {
        $this->authorize('create', Company::class);

        $userId = (int) $request->input('user_id');
        $name = $request->input('name');
        
        $company = dispatch_sync( new CreateCompany($userId, $name) );

        return (new CompanyResource($company))->response($request);
    }
}
