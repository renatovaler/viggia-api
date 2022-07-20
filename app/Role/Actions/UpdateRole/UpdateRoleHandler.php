<?php declare(strict_types=1);

namespace App\Role\Actions\UpdateRole;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Role\Models\Role;
use App\Role\Actions\RoleInformationDto;
use App\Role\Actions\UpdateRole\UpdateRole;

final class UpdateRoleHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Role\Actions\UpdateRole\UpdateRole $command
     * @return \App\Role\Actions\RoleInformationDto
     */
    public function handle(UpdateRole $command): RoleInformationDto
    {
        try {
            DB::beginTransaction();
				$role = Role::where('id', $command->roleId)->firstOrFail();
                $role->forceFill([
					'name' => Str::slug($command->name),
					'description' => $command->description
                ])->save();
            DB::commit();
			return RoleInformationDto::fromModel($role);
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
