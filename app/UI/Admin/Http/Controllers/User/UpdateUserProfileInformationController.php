<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Controllers\User;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\UI\Admin\Http\Resources\User\UserResource;
use App\UI\Admin\Http\Requests\User\UpdateUserPersonalInformationRequest;
use App\Domain\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformationCommand;

class UpdateUserProfileInformationController extends Controller
{
    /**
     * Update another user profile information (not myself)
     *
     * @param App\UI\Admin\Http\Requests\UpdateUserPersonalInformationRequest $request
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
