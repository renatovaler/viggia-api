<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use Illuminate\Http\Response;
use App\Structure\Http\Controllers\Controller;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\DeleteCompany\DeleteCompanyCommand;

class DeleteCompanyController extends Controller
{
    /**
	 * Delete company record by id
     *
     * @param  int $companyId
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $companyId): Response
    {
        $company = Company::where('id', $companyId )->first();

        $this->authorize('delete', $company);
		
        dispatch_sync(
			new DeleteCompanyCommand($companyId)
		);
        
        return response()->noContent();
    }
}
