<?php declare(strict_types=1);

namespace App\UI\Http\User\Controllers;

use App\UI\Http\User\Resources\UserResource;
use App\Structure\Http\Controllers\Controller;

class GetMyselfProfileInformationController extends Controller
{
    /**
     * Get authenticated user profile information (myself information)
     *
     * @return \App\UI\Http\User\Resources\UserResource
     */
    public function __invoke(): UserResource
    {
        return new UserResource(auth()->user());
    }
}
