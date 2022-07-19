<?php declare(strict_types=1);

namespace App\User\Policies;

use App\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasUserRoleByName('user-admin');
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasUserRoleByName('user-admin');
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasUserRoleByName('user-admin');
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->hasUserRoleByName('user-admin');
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasUserRoleByName('user-admin');
    }

    /**
     * Determine whether the user can restore the user.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function restore(User $user): bool
    {
        return $user->hasUserRoleByName('user-admin');
    }

    /**
     * Determine whether the user can permanently delete the user.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->hasUserRoleByName('user-admin');
    }

    /**
     * Determine whether the user can impersonate another user.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function canImpersonate(User $user): bool
    {
        return $user->hasUserRoleByName('user-admin');
    }

    /**
     * Determine whether the user can be impersonate for another user.
	 * This user parameter refers to the TARGET USER and NOT the logged in user..
     *
	 * Example-> Gate::inspect('canBeImpersonated', $userToBeImpersonate);
	 *
     * @param  \App\Models\User\User  $targetUser
     * @return bool
     */
    public function canBeImpersonated(User $targetUser): bool
    {
        return $targetUser->can_be_impersonated == 1;
    }
}
