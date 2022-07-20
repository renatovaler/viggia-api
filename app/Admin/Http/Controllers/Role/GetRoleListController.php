<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\Role;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Resources\Role\RoleCollection;

use App\Role\Actions\GetRoleList\GetRoleList;

class GetRoleListController extends Controller
{
    /**
     * Get list of roles
     *
     * @return \App\Admin\Http\Resources\Role\RoleCollection
     */
    public function __invoke(): RoleCollection
    {
        $roles = dispatch_sync( new GetRoleList() );
        return ( new RoleCollection($roles) );
    }
}
