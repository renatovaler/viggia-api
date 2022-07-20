<?php declare(strict_types=1);

namespace App\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\User\Http\Resources\UserResource;
use App\User\Http\Requests\UpdateMyselfPersonalInformationRequest;

use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformation;

class UpdateMyselfProfileInformationController extends Controller
{
    /**
     * Update authenticated user profile information (myself information)
     *
     * @param App\User\Http\Requests\UpdateMyselfPersonalInformationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateMyselfPersonalInformationRequest $request): JsonResponse
    {
        $userUpdated = dispatch_sync(new UpdateUserPersonalInformation(
            auth()->user()->id,
            $request->input('name'),
            $request->input('email')
        ));
        return ( new UserResource($userUpdated) )->response($request);
    }
}
