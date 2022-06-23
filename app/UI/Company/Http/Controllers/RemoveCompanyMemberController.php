<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\UI\Company\Http\Requests\RemoveCompanyMemberRequest;
use App\Domain\Company\Actions\RemoveCompanyMember\RemoveCompanyMemberCommand;

class RemoveCompanyMemberController extends Controller
{
    /**
     * Remove member to the company
     *
     * @param App\UI\Company\Http\Requests\RemoveCompanyMemberRequest $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function __invoke(RemoveCompanyMemberRequest $request): JsonResponse
    {
        dispatch_sync(
            new RemoveCompanyMemberCommand(
				$request->input('company_id'),
				$request->input('user_id'),
			)
        );
		return response()->json([
			'message' => __("Success! The user has been removed from this company's membership list.")
		], 200);
    }
}
