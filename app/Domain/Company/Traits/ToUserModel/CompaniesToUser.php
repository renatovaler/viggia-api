<?php declare(strict_types=1);

namespace App\Domain\Company\Traits\ToUserModel;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyMember;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

trait CompaniesToUser
{
    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, CompanyMember::class, 'user_id', 'company_id');
    }

    /**
     * Get all user system roles
     *
     * @return SupportCollection|EloquentCollection
     */
    public function getCompanies(): SupportCollection|EloquentCollection
    {
        $companies = $this->companies();
        return $companies->isEmpty() ? collect([]) : $companies;
    }

    /**
     * Determine if the given team is the current team.
     *
     * @param  int  $companyId
     * @return bool
     */
    public function isCurrentCompany(int $companyId): bool
    {
        return ($companyId === $this->currentCompany->id);
    }

    /**
     * Has-one relation with the current selected company model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentCompany(): HasOne
    {
        return $this->hasOne(Company::class,  'id', 'current_company_id');
    }

    /**
     * Switch the user's context to the given company.
     *
     * @param  App\Domain\Company\Models\Company  $company
     * @return bool
     */
    public function switchCompany(Company $company): bool
    {
        if (! $this->belongsToCompany($company, $this->id)) {
            return false;
        }

        $this->forceFill([
            'current_company_id' => $company->id,
        ])->save();

        $this->setRelation('currentCompany', $company);

        return true;
    }

    /**
     * Get all of the companies the user owns or belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Collection as EloquentCollection
     */
    public function allCompanies(): EloquentCollection
    {
        return $this->ownedCompanies->merge($this->companies)->sortBy('name');
    }

    /**
     * Get all user system roles
     *
     * @return SupportCollection|EloquentCollection
     */
    public function getAllCompanies(): SupportCollection|EloquentCollection
    {
        $companies = $this->allCompanies();
        return $companies->isEmpty() ? collect([]) : $companies;
    }

    /**
     * Get all of the companies the user owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedCompanies(): HasMany
    {
        return $this->hasMany(Company::class, 'user_id', 'id');
    }

    /**
     * Verifica se o usuário é dono da empresa especificada.
     *
     * @param  int $companyOwnerUserId <$company->owner_user_id>
     * @return bool
     */
    public function isOwnerOfCompany(int $companyOwnerUserId): bool
    {
        return ($this->id === $companyOwnerUserId);
    }

    /**
     * Determine if the user belongs to the given company.
     *
     * @param  App\Domain\Company\Models\Company  $company
     * @param  int  $userId
     * @return bool
     */
    public function belongsToCompany(Company $company, int $userId): bool
    {
        return $this->getAllCompanies()->contains(function ($c) use ($company) {
            return $c->id === $company->id;
        }) || $this->isOwnerOfCompany($company->user_id);
    }
}
