<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member\Invite;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

use App\Structure\Http\Controllers\Controller;
use App\Company\Models\CompanyInvitation;
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
        $invite = (new CompanyInvitation())->findOrFail($invitation);
        $now = Carbon::now();
        $expiresIn = Carbon::parse($invite->expires_in);

        if ( true === ($expiresIn < $now) ) {
            throw ValidationException::withMessages([
                'invite_expired' => __('This invite has been expired!')
            ]);
        }

        dispatch_sync(new DeleteInviteToBeCompanyMember($invitation));

        return response()->json(['Success! Invite has been refused.', 200]);
    }
}
