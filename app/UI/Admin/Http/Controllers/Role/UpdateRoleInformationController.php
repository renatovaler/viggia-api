<?php declare(strict_types=1);

namespace App\UI\Admin\Http\Controllers\Role;

use Illuminate\Http\JsonResponse;

use App\Structure\Http\Controllers\Controller;

use App\UI\Admin\Http\Resources\Role\RoleResource;
use App\UI\Admin\Http\Requests\Role\UpdateRoleRequest;

use App\Domain\Role\Actions\UpdateRole\UpdateRoleCommand;

class UpdateRoleInformationController extends Controller
{
    /**
     * Update another role profile information
     *
     * @param App\UI\Admin\Http\Requests\Role\UpdateRoleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateRoleRequest $request): JsonResponse
    {
        $roleUpdated = dispatch_sync(new UpdateRoleCommand(
            (int) $request->id,
            $request->name,
            $request->description
        ));
        return (new RoleResource($roleUpdated))->response($request);
    }
}
