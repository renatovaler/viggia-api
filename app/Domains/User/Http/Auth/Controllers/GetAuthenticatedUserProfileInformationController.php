<?php declare(strict_types=1);

namespace App\Domains\User\Http\Auth\Controllers;

use App\Kernel\Http\Controllers\Controller;
use App\Domains\User\Http\Resources\UserResource;

class GetAuthenticatedUserProfileInformationController extends Controller
{
    /**
     * Get user profile information
     *
     * @return \App\Domains\User\Http\Resources\UserResource
     */
    public function __invoke(): UserResource
    {
        return new UserResource(auth()->user());
    }
}
