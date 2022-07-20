<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\Company\Http\Requests\RemoveCompanyMemberRequest;
use App\Company\Actions\RemoveCompanyMember\RemoveCompanyMember;

class RemoveCompanyMemberController extends Controller
{
    /**
     * Remove member to the company
     *
     * @param App\Company\Http\Requests\RemoveCompanyMemberRequest $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function __invoke(RemoveCompanyMemberRequest $request): JsonResponse
    {
        dispatch_sync(
            new RemoveCompanyMember(
				$request->input('company_id'),
				$request->input('user_id'),
			)
        );
		return response()->json([
			'message' => __("Success! The user has been removed from this company's membership list.")
		], 200);
    }
}
