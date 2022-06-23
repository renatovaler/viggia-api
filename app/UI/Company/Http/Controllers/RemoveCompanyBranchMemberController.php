<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\UI\Company\Http\Requests\RemoveCompanyBranchMemberRequest;
use App\Domain\Company\Actions\RemoveCompanyBranchMember\RemoveCompanyBranchMemberCommand;

class RemoveCompanyBranchMemberController extends Controller
{
    /**
     * Remove member to the company branch
     *
     * @param App\UI\Company\Http\Requests\RemoveCompanyBranchMemberRequest $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function __invoke(RemoveCompanyBranchMemberRequest $request): JsonResponse
    {
        dispatch_sync(
            new RemoveCompanyBranchMemberRequestCommand(
				$request->input('company_branch_id'),
				$request->input('user_id'),
			)
        );
		return response()->json([
			'message' => __("Success! The user has been removed from this company branch's membership list.")
		], 200);
    }
}
