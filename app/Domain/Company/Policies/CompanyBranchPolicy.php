<?php declare(strict_types=1);

namespace App\Domain\Company\Policies;

use App\Domain\Company\Models\CompanyBranch;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyBranchPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param  \App\Models\Company\CompanyBranch  $companyBranch
     * @return bool
     */
    public function viewAny(CompanyBranch $companyBranch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\Models\Company\CompanyBranch  $companyBranch
     * @return bool
     */
    public function view(CompanyBranch $companyBranch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Models\Company\CompanyBranch  $companyBranch
     * @return bool
     */
    public function create(CompanyBranch $companyBranch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\Models\Company\CompanyBranch  $companyBranch
     * @return bool
     */
    public function update(CompanyBranch $companyBranch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\Models\Company\CompanyBranch  $companyBranch
     * @return bool
     */
    public function delete(CompanyBranch $companyBranch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the user.
     *
     * @param  \App\Models\Company\CompanyBranch  $companyBranch
     * @return bool
     */
    public function restore(CompanyBranch $companyBranch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the user.
     *
     * @param  \App\Models\Company\CompanyBranch  $companyBranch
     * @return bool
     */
    public function forceDelete(CompanyBranch $companyBranch): bool
    {
        return false;
    }
}
