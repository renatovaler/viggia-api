<?php declare(strict_types=1);

namespace App\User\Http\Controllers;

use App\User\Http\Resources\UserResource;

use App\Structure\Http\Controllers\Controller;

class GetMyselfProfileInformationController extends Controller
{
    /**
     * Get authenticated user profile information (myself information)
     *
     * @return \App\User\Http\Resources\UserResource
     */
    public function __invoke(): UserResource
    {
        $user = auth()->user();
        return ( new UserResource($user) );
    }
}
