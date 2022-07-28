<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\Role;
use Illuminate\Http\JsonResponse;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Resources\Role\RoleResource;
use App\Admin\Http\Requests\Role\UpdateRoleRequest;

use App\Role\Models\Role;
use App\Role\Actions\UpdateRole\UpdateRole;

class UpdateRoleInformationController extends Controller
{
    /**
     * Update another role profile information
     *
     * @param App\Admin\Http\Requests\Role\UpdateRoleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateRoleRequest $request): JsonResponse
    {
        $this->authorize('update', Role::class);

        $roleUpdated = dispatch_sync(new UpdateRole(
            (int) $request->id,
            $request->name,
            $request->description
        ));
        return (new RoleResource($roleUpdated))->response($request);
    }
}
