<?php declare(strict_types=1);

namespace App\Domain\Company\Traits\ToUserModel;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyMember;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * @return bool|\Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentCompany(): bool|HasOne
    {
        if (is_null($this->current_company_id) && $this->id) {
            return false; // or exception?
        }
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
        if (! $this->belongsToCompany($company)) {
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allCompanies(): Collection
    {
        return $this->ownedCompanies->merge($this->companies)->sortBy('name');
    }

    /**
     * Get all of the companies the user owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedCompanies(): HasMany
    {
        return $this->hasMany(Company::class);
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
     * @param  mixed  $company
     * @return bool
     */
    public function belongsToCompany($company): bool
    {
        return $this->companies->contains(function ($c) use ($company) {
            return $c->id === $company->id;
        }) || $this->isOwnerOfCompany($company->owner_user_id);
    }
}
