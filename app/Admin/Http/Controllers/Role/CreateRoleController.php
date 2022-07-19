<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\Role;


use Illuminate\Http\JsonResponse;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Resources\Role\RoleResource;
use App\Admin\Http\Requests\Role\CreateRoleRequest;

use App\Role\Actions\CreateRole\CreateRoleCommand;

class CreateRoleController extends Controller
{    

	/**
     * Creates a new role
     *
     * @param App\Admin\Http\Requests\Role\CreateRoleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreateRoleRequest $request): JsonResponse
    {
        $role = dispatch_sync(new CreateRoleCommand(
            $request->name,
            $request->description
        ));
		
        return ( new RoleResource($role) )->response($request);
    }
}
