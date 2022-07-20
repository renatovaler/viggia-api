<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\User;

use Illuminate\Http\Response;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Requests\User\UpdateUserPasswordRequest;

use App\User\Actions\UpdateUserPassword\UpdateUserPassword;

class UpdateUserPasswordController extends Controller
{
    /**
     * Update another user passwords
     *
     * @param App\Admin\Http\Requests\User\UpdateUserPasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateUserPasswordRequest $request): Response
    {
        $userUpdated = dispatch_sync(new UpdateUserPassword(
            (int) $request->input('id'),
            $request->input('password')
        ));
        return response()->noContent();
    }
}
