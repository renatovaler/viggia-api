<?php declare(strict_types=1);

namespace App\Domain\Role\Actions\GetRoleInformation;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\Role\Models\Role;
use App\Domain\Role\Actions\RoleInformationDto;
use App\Domain\Role\Actions\GetRoleInformation\GetRoleInformationCommand;

final class GetRoleInformationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Role\Actions\GetRoleInformation\GetRoleInformationCommand $command
     * @return \App\Domain\Role\Actions\RoleInformationDto
     */
    public function handle(GetRoleInformationCommand $command): RoleInformationDto
    {
        try {
            $role = Role::where('id', $command->roleId)->firstOrFail();
			return RoleInformationDto::fromModel($role);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 500);
        } catch(ModelNotFoundException $e) {
            throw new Exception(__('The informed role does not exist in our database.'), 404);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
