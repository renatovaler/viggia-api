<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member\Invite;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\Company\Models\Company;
use App\Company\Http\Requests\InviteNewCompanyMemberRequest;
use App\Company\Actions\InviteNewCompanyMember\InviteNewCompanyMember;

class InviteNewCompanyMemberController extends Controller
{
    /**
     * Invite new member to the company
     *
     * @param App\Company\Http\Requests\InviteNewCompanyMemberRequest $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function __invoke(InviteNewCompanyMemberRequest $request): JsonResponse
    {
        $this->authorize('invite', auth()->user()->currentCompany);

        dispatch_sync(
            new InviteNewCompanyMember(
				$request->input('company_id'),
				$request->input('email'),
                $request->input('roles')
			)
        );
        return response()->json([
			'message' => __('Success! The user has been invited.')
		], 200);
    }
}
