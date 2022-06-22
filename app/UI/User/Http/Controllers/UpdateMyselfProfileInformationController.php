<?php declare(strict_types=1);

namespace App\UI\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\UI\User\Http\Resources\UserResource;
use App\UI\User\Http\Requests\UpdateMyselfPersonalInformationRequest;
use App\Domain\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;

class UpdateMyselfProfileInformationController extends Controller
{
    /**
     * Update authenticated user profile information (myself information)
     *
     * @param App\UI\User\Http\Requests\UpdateMyselfPersonalInformationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateMyselfPersonalInformationRequest $request): JsonResponse
    {
        $userUpdated = dispatch_sync(new UpdateUserPersonalInformationCommand(
            auth()->user()->id,
            $request->input('name'),
            $request->input('email')
        ));
        return (new UserResource($userUpdated))->response($request);
    }
}
