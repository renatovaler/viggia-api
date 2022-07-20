<?php declare(strict_types=1);

namespace App\MyselfUser\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\MyselfUser\Http\Resources\MyselfUserResource;
use App\MyselfUser\Http\Requests\UpdateMyselfPersonalInformationRequest;

use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformation;

class UpdateMyselfProfileInformationController extends Controller
{
    /**
     * Update authenticated user profile information (myself information)
     *
     * @param App\MyselfUser\Http\Requests\UpdateMyselfPersonalInformationRequest $request
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
        return ( new MyselfUserResource($userUpdated) )->response($request);
    }
}
