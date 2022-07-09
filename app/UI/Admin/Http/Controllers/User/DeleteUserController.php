<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Controllers\User;

use Illuminate\Http\Response;
use App\Structure\Http\Controllers\Controller;

use App\Domain\Admin\Actions\DeleteUser\DeleteUserCommand;

class DeleteUserController extends Controller
{
    /**
	 * Delete user by id
     *
     * @param  int $userId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $userId): Response
    {
        dispatch_sync( new DeleteUserCommand($userId) );
        return response()->noContent();
    }
}
