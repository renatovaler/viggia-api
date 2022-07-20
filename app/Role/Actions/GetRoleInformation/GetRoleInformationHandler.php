<?php declare(strict_types=1);

namespace App\Role\Actions\GetRoleInformation;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Role\Models\Role;
use App\Role\Actions\RoleInformationDto;
use App\Role\Actions\GetRoleInformation\GetRoleInformation;

final class GetRoleInformationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Role\Actions\GetRoleInformation\GetRoleInformation $command
     * @return \App\Role\Actions\RoleInformationDto
     */
    public function handle(GetRoleInformation $command): RoleInformationDto
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
