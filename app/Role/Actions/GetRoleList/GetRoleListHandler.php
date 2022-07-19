<?php declare(strict_types=1);

namespace App\Role\Actions\GetRoleList;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;

use App\Role\Models\Role;
use App\Role\Actions\RoleInformationListDto;

final class GetRoleListHandler
{
    /**
     * Executa a ação
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function handle(): Collection
    {
        try {
            $roles = Role::all();
            return RoleInformationListDto::fromCollection($roles);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 500);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
