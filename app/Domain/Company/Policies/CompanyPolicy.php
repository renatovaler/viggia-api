<?php declare(strict_types=1);

namespace App\Domain\Company\Policies;

use App\Domain\Company\Models\Company;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param  \App\Models\Company\Company  $company
     * @return bool
     */
    public function viewAny(Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\Models\Company\Company  $company
     * @return bool
     */
    public function view(Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Models\Company\Company  $company
     * @return bool
     */
    public function create(Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\Models\Company\Company  $company
     * @return bool
     */
    public function update(Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\Models\Company\Company  $company
     * @return bool
     */
    public function delete(Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the user.
     *
     * @param  \App\Models\Company\Company  $company
     * @return bool
     */
    public function restore(Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the user.
     *
     * @param  \App\Models\Company\Company  $company
     * @return bool
     */
    public function forceDelete(Company $company): bool
    {
        return false;
    }
}
