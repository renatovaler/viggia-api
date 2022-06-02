<?php declare(strict_types=1);

namespace App\UI\Http\User\Controllers;

use App\UI\Http\User\Resources\UserResource;
use App\Structure\Http\Controllers\Controller;
use App\UI\Http\User\Requests\UpdateUserPersonalInformationRequest;

use App\Domain\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;
use Illuminate\Http\JsonResponse;

class UpdateMyselfProfileInformationController extends Controller
{
    /**
     * Update authenticated user profile information (myself information)
     *
     * @param App\UI\Http\User\Requests\UpdateUserPersonalInformationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateUserPersonalInformationRequest $request): JsonResponse
    {
        $userUpdated = dispatch_sync(new UpdateUserPersonalInformationCommand(
            intval($request->input('id')),
            $request->input('name'),
            $request->input('email')
        ));
        return (new UserResource($userUpdated))->response($request);
    }
}
