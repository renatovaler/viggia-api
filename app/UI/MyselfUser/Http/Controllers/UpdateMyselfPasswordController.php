<?php declare(strict_types=1);

namespace App\UI\MyselfUser\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\UI\MyselfUser\Http\Resources\UserResource;
use App\UI\MyselfUser\Http\Requests\UpdateMyselfPasswordRequest;

use App\Domain\User\Actions\UpdateUserPassword\UpdateUserPasswordCommand;

class UpdateMyselfPasswordController extends Controller
{
    /**
     * Update authenticated user password (myself password)
     *
     * @param App\UI\MyselfUser\Http\Requests\UpdateMyselfPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateMyselfPasswordRequest $request): JsonResponse
    {
        $userUpdated = dispatch_sync(new UpdateUserPasswordCommand(
            auth()->user()->id,
            $request->input('password')
        ));
        return ( new UserResource($userUpdated) )->response($request);
    }
}
