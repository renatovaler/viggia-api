<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\CompanyBranch\Member;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\Company\Http\Requests\AddCompanyBranchMemberRequest;
use App\Company\Actions\AddCompanyBranchMember\AddCompanyBranchMember;

class AddCompanyBranchMemberController extends Controller
{
    /**
     * Add member to the company branch
     *
     * @param App\Company\Http\Requests\AddCompanyBranchMemberRequest $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function __invoke(AddCompanyBranchMember $request): JsonResponse
    {
        dispatch_sync(
            new AddCompanyBranchMember(
				$request->input('company_branch_id'),
				$request->input('user_id'),
			)
        );
		return response()->json([
			'error' => false,
			'message' => __('Success! The user has been added as a member of this company branch.')
		], 200);
    }
}
