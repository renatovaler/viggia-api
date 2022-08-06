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
     * @return \Illuminate\Http\JsonResponse
    *
    * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(string $invitation): JsonResponse
    {
        // Aqui usamos firstOfFail(), pois se o convite não existe, tem que stopar
        $invite = (new CompanyInvitation())->where('token', $invitation)->firstOrFail();

        /*
        * Não pode usar firstOfFail(), apenas first()
        * Se o usuário não existir, o script não pode stopar
        * pois nesse caso o convidado tem que poder se cadastrar
        */        
        $user = (new User())->where('email', $invite->email)->first();

        $response = [
			'id' => $invite->id,
			'token' => $invite->token,
			'companyId' => $invite->company_id,
            'user' => collect([
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name
            ]),
            'roles' => $invite->roles,
            'expired' => (Carbon::parse($invite->expires_in) < Carbon::now()),
            'expires_in' => $invite->expires_in
        ];

        return (new CompanyInviteResource($response))->response($request);
    }
}
