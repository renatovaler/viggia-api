<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Controllers\Role;

use Illuminate\Http\Response;
use App\Structure\Http\Controllers\Controller;

use App\Domain\Admin\Actions\DeleteRole\DeleteRoleCommand;

class DeleteRoleController extends Controller
{
    /**
	 * Delete role by id
     *
     * @param  int $roleId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $roleId): Response
    {
        dispatch_sync( new DeleteRoleCommand($roleId) );
        return response()->noContent();
    }
}
