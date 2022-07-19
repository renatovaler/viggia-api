<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\User;

use Illuminate\Http\JsonResponse;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Resources\User\UserResource;
use App\Admin\Http\Requests\User\UpdateUserPersonalInformationRequest;

use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;

class UpdateUserProfileInformationController extends Controller
{
    /**
     * Update another user profile information
     *
     * @param App\Admin\Http\Requests\User\UpdateUserPersonalInformationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateUserPersonalInformationRequest $request): JsonResponse
    {
        $userUpdated = dispatch_sync(new UpdateUserPersonalInformationCommand(
            (int) $request->input('id'),
            $request->input('name'),
            $request->input('email')
        ));
        return (new UserResource($userUpdated))->response($request);
    }
}
