<?php declare(strict_types=1);

namespace App\Domain\Role\Traits\ToUserModel;

use App\Domain\Role\Models\Role;
use App\Domain\Role\Models\RoleUser;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Esta trait deve ser usada na model "App\Domain\Models\User".
 * Possui um conjunto de métodos relativos às regras de
 * permissões de usuário em relação às FILIAIS DAS EMPRESAS e aos MEMBROS DAS FILIAIS.
 */
trait RoleToUserForCompanyBranch
{
    /**
     * Get all the functions of the current company branch
     *
     * @param int $companyBranchId
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companyBranchRoles(int $companyBranchId): BelongsToMany
    {
        return $this->belongsToMany(Role::class, RoleUser::class, 'user_id', 'role_id')
                            ->where('company_branch_id', $companyBranchId);
    }

    /**
     * Checks if the user has a specific company branch role
     *
     * @param string $name
     * @param int $companyBranchId
     * @return bool
     */
    public function hasUserCompanyBranchRoleByName(string $name, int $companyBranchId): bool
    {
        return $this->companyBranchRoles($companyBranchId)->contains('name', $name);
    }

    /**
     * Attach role to company branch member
     *
     * @param int|array $roleIds
     * @param int $companyBranchId
     * @return void
     */
    public function addRoleToCompanyBranchMember(int|array $roleIds, int $companyBranchId): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (is_null($this->companyBranchRoles($companyBranchId)) || ! $this->companyBranchRoles($companyBranchId)->contains('role_id', $id)) {
                $this->companyBranchRoles($companyBranchId)->attach($roleIds, ['user_id' => $this->id]);
            }
        }
    }

    /**
     * Attach role to company branch member by role name
     *
     * @param string $name
     * @param int $companyBranchId
     * @return bool
     */
    public function addRoleToCompanyBranchMemberByName(string $name, int $companyBranchId): bool
    {
        $role = Role::where('name', $name)->first();
        return $role->count() > 0 ? $this->addRoleToCompanyBranchMember($role->id, $companyBranchId) : false;
    }

    /**
     * Remove role to company branch member
     *
     * @param int|array $roleIds
     * @param int $companyBranchId
     * @return void
     */
    public function removeRoleToCompanyBranchMember(int|array $roleIds, int $companyBranchId): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->companyBranchRoles($companyBranchId)->contains('role_id', $id)) {
                $this->companyBranchRoles($companyBranchId)->detach($roleIds, ['user_id' => $this->id]);
            }
        }
    }

    /**
     * Remove role to company branch member by role name
     *
     * @param string $name
     * @param int $companyBranchId
     * @return bool
     */
    public function removeRoleToCompanyBranchMemberByName(string $name, int $companyBranchId): bool
    {
        $role = Role::where('name', $name)->first();
        return $role->count() > 0 ? $this->removeRoleToCompanyBranchMember($role->id, $companyBranchId) : false;
    }

    /**
     * Remove all roles to company branch member
     *
     * @param int $companyBranchId
     * @return void
     */
    public function flushRolesToCompanyBranchMember(int $companyBranchId): void
    {
        $this->companyBranchRoles($companyBranchId)->where('user_id', $this->id)->detach();
    }

    /**
     * Sync roles to company branch member
     *
     * @param int|array $roleIds
     * @param int $companyBranchId
     * @return void
     */
    public function syncRolesToCompanyBranchMember(int|array $roleIds, int $companyBranchId): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->companyBranchRoles($companyBranchId)->contains('role_id', $id)) {
                $this->companyBranchRoles($companyBranchId)->sync($roleIds, ['user_id' => $this->id]);
            }
        }
    }
}
