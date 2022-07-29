<?php declare(strict_types=1);

namespace App\Admin\Policies;

use App\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
        //return $user->hasUserRoleByName('super_admin');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return true;
        //return $user->hasUserRoleByName('super_admin');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasUserRoleByName('super_admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $user->hasUserRoleByName('super_admin');
    }

    /**
     * Determine whether the user can switch company.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function switchCompany(User $user)
    {
        return $user->hasUserRoleByName('super_admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $user->hasUserRoleByName('super_admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user)
    {
        return $user->hasUserRoleByName('super_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user)
    {
        return $user->hasUserRoleByName('super_admin');
    }
}
