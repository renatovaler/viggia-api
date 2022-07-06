<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\Domain\Company\Actions\DeleteCompany\DeleteCompanyCommand;

class DeleteCompanyController extends Controller
{
    /**
	 * Delete company record by id
     *
     * @param  int $companyId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $companyId): JsonResponse
    {
		// Falta fazer validação/autorização
		
        $companyId = dispatch_sync(
			new DeleteCompanyCommand($companyId)
		);
        return response()->noContent();
    }
}
