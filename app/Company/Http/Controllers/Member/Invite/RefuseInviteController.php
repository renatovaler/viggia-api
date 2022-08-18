<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member\Invite;

use Illuminate\Http\JsonResponse;

use App\Structure\Http\Controllers\Controller;
use App\Company\Actions\DeleteInviteToBeCompanyMember\DeleteInviteToBeCompanyMember;

class RefuseInviteController extends Controller
{
    /**
     * Refuse a company invitation
     *
     * @param int $invitation
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $invitation): JsonResponse
    {
        dispatch_sync(new DeleteInviteToBeCompanyMember($invitation));

        return response()->json(['Success! Invite has been refused.', 200]);
    }
}
