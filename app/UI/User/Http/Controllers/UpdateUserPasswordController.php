<?php declare(strict_types=1);

namespace App\UI\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\UI\User\Http\Resources\UserResource;
use App\UI\User\Http\Requests\UpdateUserPasswordRequest;
use App\Domain\User\Actions\UpdateUserPassword\UpdateUserPasswordCommand;

class UpdateUserPasswordController extends Controller
{
    /**
     * Update another user passwords (not myself)
     *
     * @param App\UI\User\Http\Requests\UpdateUserPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateUserPasswordRequest $request): JsonResponse
    {
        $userUpdated = dispatch_sync(new UpdateUserPasswordCommand(
            intval( $request->input('id') ),
            $request->input('password')
        ));
        return (new UserResource($userUpdated))->response($request);
    }
}
