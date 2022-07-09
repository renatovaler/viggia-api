<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Controllers\Role;

use App\Structure\Http\Controllers\Controller;

use App\UI\Admin\Http\Resources\Role\RoleCollection;

use App\Domain\Role\Actions\GetRoleList\GetRoleListCommand;

class GetRoleListController extends Controller
{
    /**
     * Get list of roles
     *
     * @return \App\UI\Admin\Http\Resources\Role\RoleCollection
     */
    public function __invoke(): RoleCollection
    {
        $roles = dispatch_sync( new GetRoleListCommand() );
        return ( new RoleCollection($roles) );
    }
}
