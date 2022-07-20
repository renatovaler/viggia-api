<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\User;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Resources\User\UserResource;

use App\User\Actions\GetUser\GetUser;

class GetUserProfileInformationByIdController extends Controller
{
    /**
     * Get another user profile information by user_id
     *
     * @param  int $userId
     *
     * @return \App\Admin\Http\Resources\User\UserResource
     */
    public function __invoke(int $userId): UserResource
    {
        $user = dispatch_sync( new GetUser($userId) );
        return ( new UserResource($user) );
    }
}
