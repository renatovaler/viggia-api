<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\User;

use Illuminate\Http\Response;
use App\Structure\Http\Controllers\Controller;

use App\Admin\Actions\DeleteUser\DeleteUser;

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
        dispatch_sync( new DeleteUser($userId) );
        return response()->noContent();
    }
}
