<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Controllers\User;

use App\UI\Admin\Http\Resources\User\UserResource;
use App\Structure\Http\Controllers\Controller;
use App\Domain\User\Actions\GetUser\GetUserById\GetUserByIdCommand;

class GetUserProfileInformationByIdController extends Controller
{
    /**
     * Get another user profile information by user_id (not myself)
     *
     * @param  int $userId
     *
     * @return \App\UI\Admin\Http\Resources\UserResource
     */
    public function __invoke(int $userId): UserResource
    {
        $user = dispatch_sync( new GetUserByIdCommand($userId) );
        return (new UserResource($user));
    }
}
