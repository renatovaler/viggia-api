<?php declare(strict_types=1);

namespace App\Role\Actions\CreateRole;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Role\Models\Role;
use App\Role\Actions\RoleInformationDto;
use App\Role\Actions\CreateRole\CreateRole;

final class CreateRoleHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Role\Actions\CreateRole\CreateRole $command
     * @return RoleInformationDto
     */
    public function handle(CreateRole $command): RoleInformationDto
    {
        try {
            DB::beginTransaction();
				$role = Role::create([
					'name' => Str::slug($command->name),
					'description' => $command->description
				]);
            DB::commit();
			return RoleInformationDto::fromModel($role);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
