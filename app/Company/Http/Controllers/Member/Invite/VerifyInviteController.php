<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member\Invite;

use Illuminate\Http\Response;

use Carbon\Carbon;

use App\User\Models\User;

use App\Company\Models\CompanyInvitation;
use App\Company\Http\Resources\CompanyInviteResource;

use App\Structure\Http\Controllers\Controller;

class VerifyInviteController extends Controller
{

    /**
     * Verify a company invitation
     *
     * @param string $invitation
     *
     * @return \App\Company\Http\Resources\CompanyInviteResource
    *
    * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(string $invitation): CompanyInviteResource
    {
        // Aqui usamos firstOrFail(), pois se o convite não existe, tem que stopar
        $invite = (new CompanyInvitation())->where('token', $invitation)->firstOrFail();

        return (new CompanyInviteResource($invite));
    }
}
