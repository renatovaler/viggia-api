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
 * permissões de usuário em relação AO SISTEMA GERAL.
 */
trait RoleToUserForSystem
{
    /**
     * Get relation of user system roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function systemRoles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, RoleUser::class, 'user_id', 'role_id');
    }

    /**
     * Get all user system roles
     *
     * @return SupportCollection|EloquentCollection
     */
    public function getSystemRoles(): SupportCollection|EloquentCollection
    {
        $systemRoles = $this->systemRoles()->get();
        return $systemRoles->isEmpty() ? collect([]) : $systemRoles;
    }

    /**
     * Checks if the user has a specific system role
     *
     * @param string $name
     * @return bool
     */
    public function hasUserRoleByName(string $name = null): bool
    {
        return $this->getSystemRoles()->contains('name', $name);
    }

    /**
     * Attach system role to user
     *
     * @param int|array $roleIds
     * @return void
     */
    public function addRoleToUser(int|array $roleIds): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->getSystemRoles()->contains('role_id', $id)) {
                $this->systemRoles()->attach($roleIds);
            }
        }
    }

    /**
     * Attach system role to user by role name
     *
     * @param string $name
     * @return bool
     */
    public function addRoleToUserByName(string $name = null): bool
    {
        $role = Role::where('name', $name)->first();
        return (
            is_null($role) ? false :
            (
                $role->count() > 0 ?
                $this->addRoleToUser($role->id) : false
            )
        );
    }

    /**
     * Remove system role to user
     *
     * @param int|array $roleIds
     * @return void
     */
    public function removeRoleToUser(int|array $roleIds): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->getSystemRoles()->contains('role_id', $id)) {
                $this->systemRoles()->detach($roleIds);
            }
        }
    }

    /**
     * Remove system role to user by role name
     *
     * @param string $name
     * @return bool
     */
    public function removeRoleToUserByName(string $name): bool
    {
        $role = Role::where('name', $name)->first();
        return (
            is_null($role) ? false :
            (
                $role->count() > 0 ?
                $this->removeRoleToUser($role->id) : false
            )
        );
    }

    /**
     * Remove all system roles to user
     *
     * @return void
     */
    public function flushRolesToUser(): void
    {
        $this->systemRoles()->detach();
    }

    /**
     * Sync system roles to user
     *
     * @param int|array $roleIds
     * @return void
     */
    public function syncRolesToUser(int|array $roleIds): void
    {
        ! is_array($roleIds) ? $roleIds = [$roleIds]: '';
        foreach($roleIds as $role => $id)
        {
            if (! $this->getSystemRoles()->contains('role_id', $id)) {
                $this->systemRoles()->sync($roleIds);
            }
        }
    }
}
