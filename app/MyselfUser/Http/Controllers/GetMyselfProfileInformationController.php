<?php declare(strict_types=1);

namespace App\MyselfUser\Http\Controllers;

use App\MyselfUser\Http\Resources\MyselfUserResource;

use App\Structure\Http\Controllers\Controller;

class GetMyselfProfileInformationController extends Controller
{
    /**
     * Get authenticated user profile information (myself information)
     *
     * @return \App\MyselfUser\Http\Resources\MyselfUserResource
     */
    public function __invoke(): MyselfUserResource
    {
        $user = auth()->user();
        return ( new MyselfUserResource($user) );
    }
}
