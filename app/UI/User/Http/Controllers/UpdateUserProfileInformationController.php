<?php declare(strict_types=1);

namespace App\UI\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\UI\User\Http\Resources\UserResource;
use App\UI\User\Http\Requests\UpdateUserPersonalInformationRequest;
use App\Domain\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;

class UpdateUserProfileInformationController extends Controller
{
    /**
     * Update another user profile information (not myself)
     *
     * @param App\UI\User\Http\Requests\UpdateUserPersonalInformationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateUserPersonalInformationRequest $request): JsonResponse
    {
        $userUpdated = dispatch_sync(new UpdateUserPersonalInformationCommand(
            intval( $request->input('id') ),
            $request->input('name'),
            $request->input('email')
        ));
        return (new UserResource($userUpdated))->response($request);
    }
}
