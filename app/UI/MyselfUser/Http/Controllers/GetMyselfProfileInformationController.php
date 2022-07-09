<?php declare(strict_types=1);

namespace App\UI\MyselfUser\Http\Controllers;

use App\UI\MyselfUser\Http\Resources\UserResource;

use App\Structure\Http\Controllers\Controller;

class GetMyselfProfileInformationController extends Controller
{
    /**
     * Get authenticated user profile information (myself information)
     *
     * @return \App\UI\MyselfUser\Http\Resources\UserResource
     */
    public function __invoke(): UserResource
    {
        $user = auth()->user();
        return ( new UserResource($user) );
    }
}
