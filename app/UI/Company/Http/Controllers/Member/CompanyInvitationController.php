<?php declare(strict_types=1);

namespace App\UI\Company\Http\Controllers\Member;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\Domain\Company\Models\CompanyInvitation;

class CompanyInvitationController extends Controller
{
    /**
     * Accept a team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain\Company\Models\CompanyInvitation  $invitation
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept(Request $request, CompanyInvitation $invitation): JsonResponse
    {
        return response()->json(['accept invite - ok', 200]);
    }

    /**
     * Cancel the given team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain\Company\Models\CompanyInvitation  $invitation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, CompanyInvitation $invitation): JsonResponse
    {
        return response()->json(['refuse invite - ok', 200]);
    }
}
