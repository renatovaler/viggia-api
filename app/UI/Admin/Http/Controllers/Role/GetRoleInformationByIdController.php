<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Controllers\Role;

use App\Structure\Http\Controllers\Controller;

use App\UI\Admin\Http\Resources\Role\RoleResource;

use App\Domain\Role\Actions\GetRoleInformation\GetRoleInformationCommand;

class GetRoleInformationByIdController extends Controller
{
    /**
     * Get role information by id
     *
     * @param  int $roleId
     *
     * @return \App\UI\Admin\Http\Resources\Role\RoleResource
     */
    public function __invoke(int $roleId): RoleResource
    {
        $role = dispatch_sync( new GetRoleInformationCommand($roleId) );
        return ( new RoleResource($role) );
    }
}
