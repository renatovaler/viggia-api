<?php declare(strict_types=1);

namespace App\Role\Actions\DeleteRole;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Role\Models\Role;
use App\Role\Actions\DeleteRole\DeleteRole;

final class DeleteRoleHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Role\Actions\DeleteRole\DeleteRole $command
     * @return void
     */
    public function handle(DeleteRole $command): void
    {
        try {
            DB::beginTransaction();
				(new Role())->removeRole($command->roleId);
            DB::commit();
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
