<?php declare(strict_types=1);

namespace App\Domain\Role\Actions\GetCompanyMemberList;

use Exception;
use Illuminate\Database\QueryException;

use App\Domain\Role\Models\Role;
use App\Domain\Role\Actions\RoleInformationListDto;

final class GetCompanyMemberListHandler
{
    /**
     * Executa a ação
     *
     * @return \App\Domain\Role\Actions\RoleInformationListDto
     */
    public function handle(): RoleInformationListDto
    {
        try {
            $roles = Role::all();
            return RoleInformationListDto::fromCollection($roles);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 403);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
