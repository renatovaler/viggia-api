<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Controllers\User;

use Illuminate\Http\Response;

use App\Structure\Http\Controllers\Controller;

use App\UI\Admin\Http\Requests\User\UpdateUserPasswordRequest;

use App\Domain\User\Actions\UpdateUserPassword\UpdateUserPasswordCommand;

class UpdateUserPasswordController extends Controller
{
    /**
     * Update another user passwords
     *
     * @param App\UI\Admin\Http\Requests\User\UpdateUserPasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateUserPasswordRequest $request): Response
    {
        $userUpdated = dispatch_sync(new UpdateUserPasswordCommand(
            (int) $request->input('id'),
            $request->input('password')
        ));
        return response()->noContent();
    }
}
