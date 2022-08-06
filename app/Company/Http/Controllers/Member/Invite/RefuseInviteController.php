<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member\Invite;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Structure\Http\Controllers\Controller;

class RefuseInviteController extends Controller
{
    /**
     * Refuse a company invitation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['Invite has been refused.', 200]);
    }
}
