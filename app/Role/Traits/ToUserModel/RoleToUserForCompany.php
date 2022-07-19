<?php declare(strict_types=1);

namespace App\Role\Traits\ToUserModel;

use App\Role\Models\Role;
use App\Role\Models\RoleUser;

use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Esta trait deve ser usada na model "App\Models\User".
 * Possui um conjunto de métodos relativos às regras de
 * permissões de usuário em relação às EMPRESAS e aos MEMBROS DA EMPRESA.
 */
trait RoleToUserForCompany
{
    /**
     * Get relation of roles of the company
     *
     * @param int $companyId
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companyRoles(int $companyId): BelongsToMany
    {
        return $this->belongsToMany(Role::class, RoleUser::class, 'user_id', 'role_id')
                            ->where('company_id', $companyId);
    }

    /**
     * Get all the roles of the company
     *
     * @param null|int $companyId
     * @return SupportCollection|EloquentCollection
     */
    public function getCompanyRoles(null|int $companyId): SupportCollection|EloquentCollection
    {
		if( is_null($companyId) ) {
			return collect([]);
		}
        $companyRoles = $this->companyRoles($companyId)->get();
        return $companyRoles->isEmpty() ? collect([]) : $companyRoles;
    }

    /**
     * Checks if the user has a specific company role
     *
     * @param string $name
     * @param int $companyId
     * @return bool
     */
    public function hasUserCompanyRoleByName(string $name, int $companyId): bool
    {
        return $this->getCompanyRoles($companyId)->contains('name', $name);
    }

    /**
     * Attach role to company member
     *
     * @param int|array $roleIds
     * @param int $companyId
     * @return void
     */
    public function addRoleToCompanyMember(int|array $roleIds, int $companyId): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->getCompanyRoles($companyId)->contains('role_id', $id)) {
                $this->companyRoles($companyId)->attach($roleIds, ['user_id' => $this->id]);
            }
        }
    }

    /**
     * Attach role to company member by role name
     *
     * @param string $name
     * @param int $companyId
     * @return bool
     */
    public function addRoleToCompanyMemberByName(string $name, int $companyId): bool
    {
        $role = Role::where('name', $name)->first();
        return (
            is_null($role) ? false :
            (
                $role->count() > 0 ?
                $this->addRoleToCompanyMember($role->id, $companyId) : false
            )
        );
    }

    /**
     * Remove role to company member
     *
     * @param int|array $roleIds
     * @param int $companyId
     * @return void
     */
    public function removeRoleToCompanyMember(int|array $roleIds, int $companyId): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->getCompanyRoles($companyId)->contains('role_id', $id)) {
                $this->companyRoles($companyId)->detach($roleIds, ['user_id' => $this->id]);
            }
        }
    }

    /**
     * Remove role to company member by role name
     *
     * @param string $name
     * @param int $companyId
     * @return bool
     */
    public function removeRoleToCompanyMemberByName(string $name, int $companyId): bool
    {
        $role = Role::where('name', $name)->first();
        return (
            is_null($role) ? false :
            (
                $role->count() > 0 ?
                $this->removeRoleToCompanyMember($role->id, $companyId) : false
            )
        );
    }

    /**
     * Remove all roles to company member
     *
     * @param int $companyId
     * @return void
     */
    public function flushRolesToCompanyMember(int $companyId): void
    {
        $this->companyRoles($companyId)->where('user_id', $this->id)->detach();
    }

    /**
     * Sync roles to company member
     *
     * @param int|array $roleIds
     * @param int $companyId
     * @return void
     */
    public function syncRolesToCompanyMember(int|array $roleIds, int $companyId): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->getCompanyRoles($companyId)->contains('role_id', $id)) {
                $this->companyRoles($companyId)->sync($roleIds, ['user_id' => $this->id]);
            }
        }
    }
}
