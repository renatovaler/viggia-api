<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use App\Structure\Http\Controllers\Controller;
use App\UI\Company\Http\Resources\CompanyResource;
use App\UI\Company\Http\Requests\CreateCompanyRequest;
use App\Domain\Company\Actions\CreateCompany\CreateCompanyCommand;

class CreateCompanyController extends Controller
{
    /**
     * Update current user company information
     *
     * @param App\UI\Company\Http\Requests\CreateCompanyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreateCompanyRequest $request): JsonResponse
    {
        $company = dispatch_sync(new CreateCompanyCommand(
            $request->input('user_id'),
            $request->input('name')
        ));
        return (new CompanyResource($company))->response($request);
    }
}
