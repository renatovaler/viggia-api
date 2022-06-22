<?php declare(strict_types=1);

namespace App\Domain\Role\Traits\ToUserModel;

use App\Domain\Role\Models\Role;
use App\Domain\Role\Models\RoleUser;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Esta trait deve ser usada na model "App\Domain\Models\User".
 * Possui um conjunto de métodos relativos às regras de
 * permissões de usuário em relação AO SISTEMA GERAL.
 */
trait RoleToUserForSystem
{
    /**
     * Get all user roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, RoleUser::class, 'user_id', 'role_id');
    }

    /**
     * Checks if the user has a specific role
     *
     * @param string $name
     * @return bool
     */
    public function hasUserRoleByName(string $name = null): bool
    {
        return $this->roles->contains('name', $name);
    }

    /**
     * Attach role to user
     *
     * @param int|array $roleIds
     * @return void
     */
    public function addRoleToUser(int|array $roleIds): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->roles->contains('role_id', $id)) {
                $this->roles()->attach($roleIds);
            }
        }
    }

    /**
     * Attach role to user by role name
     *
     * @param string $name
     * @return bool
     */
    public function addRoleToUserByName(string $name = null): bool
    {
        $role = Role::where('name', $name)->first();
        return $role->count() > 0 ? $this->addRoleToUser($role->id) : false;
    }

    /**
     * Remove role to user
     *
     * @param int|array $roleIds
     * @return void
     */
    public function removeRoleToUser(int|array $roleIds): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->roles->contains('role_id', $id)) {
                $this->roles()->detach($roleIds);
            }
        }
    }

    /**
     * Remove role to user by role name
     *
     * @param string $name
     * @return bool
     */
    public function removeRoleToUserByName(string $name): bool
    {
        $role = Role::where('name', $name)->first();
        return $role->count() > 0 ? $this->removeRoleToUser($role->id) : false;
    }

    /**
     * Remove all roles to user
     *
     * @return void
     */
    public function flushRolesToUser(): void
    {
        $this->roles()->detach();
    }

    /**
     * Sync roles to user
     *
     * @param int|array $roleIds
     * @return void
     */
    public function syncRolesToUser(int|array $roleIds): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->roles->contains('role_id', $id)) {
                $this->roles()->sync($roleIds);
            }
        }
    }
}
