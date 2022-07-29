<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\Role;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Resources\Role\RoleResource;

use App\Role\Models\Role;
use App\Role\Actions\GetRoleInformation\GetRoleInformation;

class GetRoleInformationByIdController extends Controller
{
    /**
     * Get role information by id
     *
     * @param  int $roleId
     *
     * @return \App\Admin\Http\Resources\Role\RoleResource
     */
    public function __invoke(int $roleId): RoleResource
    {
        $this->authorize('view', Role::class);

        $role = dispatch_sync( new GetRoleInformation($roleId) );
        return ( new RoleResource($role) );
    }
}
