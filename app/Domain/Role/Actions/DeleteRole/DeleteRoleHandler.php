<?php declare(strict_types=1);

namespace App\Domain\Role\Actions\DeleteRole;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Domain\Role\Models\Role;
use App\Domain\Role\Actions\DeleteRole\DeleteRoleCommand;

final class DeleteRoleHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Role\Actions\DeleteRole\DeleteRoleCommand $command
     * @return void
     */
    public function handle(DeleteRoleCommand $command): void
    {
        try {
            DB::beginTransaction();
				Role::removeRole($command->roleId);
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
