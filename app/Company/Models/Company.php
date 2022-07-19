<?php declare(strict_types=1);

namespace App\Company\Models;

use App\User\Models\User;
use App\Company\Models\CompanyMember;
use App\Company\Models\CompanyBranch;
use App\Company\Models\CompanyInvitation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, BelongsTo, HasMany};

use Database\Factories\Company\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Define custom factory to the model
     *
     * @return void
     */
    protected static function newFactory()
    {
        return CompanyFactory::new();
    }
 
    /**
     * Purge all of the company's resources.
     *
     * @param  int $companyId
     * @return void
     */
    public function deleteCompany(int $companyId): void
    {
		$company = $this->where('id', $companyId)->firstOrFail();
		
        $company->companyOwner()->where('current_company_id', $companyId)
                ->update(['current_company_id' => null]);

        $members = $company->onlyCompanyMembers->where('current_company_id', $companyId);
    
        $members->map(function ($member) {
            $member->update(['current_company_id' => null]);
        });

        $company->onlyCompanyMembers()->detach();

        $branchs = $company->ownedCompanyBranchs;

        $branchs->map(function ($branch) {
            $branchMembers = $branch->companyBranchMembers;
            $branchMembers->map(function ($branchMember) {
                $branchMember->update(['current_company_id' => null]);
            });
            $branch->companyBranchMembers()->detach();
            $branch->delete();
        });

        $company->delete();
    }
	
    /**
     * Retorna dados do usuário que é proprietário da empresa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Retorna todos os usuários que são membros da empresa, INCLUSIVE o proprietário
     * Não inclui dados de membros das filiais, apenas da empresa matriz.
     *
     * @return \Illuminate\Database\Eloquent\Collection as EloquentCollection
     */
    public function companyMembersAndOwner(): EloquentCollection
    {
        return $this->onlyCompanyMembers->merge([$this->companyOwner]);
    }

    /**
     * Get all user system roles
     *
     * @return SupportCollection|EloquentCollection
     */
    public function getCompanyMembersAndOwner(): SupportCollection|EloquentCollection
    {
        $companyMembersAndOwner = $this->companyMembersAndOwner();
        return $companyMembersAndOwner->isEmpty() ? collect([]) : $companyMembersAndOwner;
    }

    /**
     * Retorna todos os usuários que são APENAS membros da empresa, EXCLUINDO o proprietário
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function onlyCompanyMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, CompanyMember::class)
                        ->withTimestamps()
                        ->as('company_members');
    }

    /**
     * Determina se o usuário APENAS membro da empresa.
     *
     * @param  int $userId
     * @return bool
     */
    public function hasOnlyCompanyMember(int $userId): bool
    {
        return $this->onlyCompanyMembers()->contains('id', $userId);
    }

    /**
     * Determina se o usuário é membro ou proprietário da empresa. Busca por ID do usuário.
     *
     * @param  int $userId
     * @return bool
     */
    public function hasOwnerOrCompanyMember(int $userId): bool
    {
        return $this->companyMembersAndOwner()->contains('id', $userId);
    }

    /**
     * Determina se o usuário é proprietário da empresa. Busca por ID do usuário.
     *
     * @param  int $userId
     * @return bool
     */
    public function hasCompanyOwner(int $userId): bool
    {
        return $this->companyOwner()->where('id', $userId)->exists();
    }

    /**
     * Determina se o usuário é membro ou proprietário da empresa. Busca por e-mail.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasCompanyMemberWithEmail(string $email): bool
    {
        return $this->companyMembersAndOwner()->contains('email', $email);
    }

    /**
     * Retorna informações de determinado membro da empresa (inclusive o proprietário).
	 * A busca é feita por ID do usuário.
     *
     * @param  int $companyMemberId
     * @return \Illuminate\Database\Eloquent\Collection as EloquentCollection
     */
    public function companyMemberById($companyMemberId): EloquentCollection
    {
        return $this->companyMembersAndOwner()->where('id', $companyMemberId)->firstOrFail();
    }

    /**
     * Remove membro da empresa.
     *
     * @param  \App\User\Models\User  $user
     * @return void
     */
    public function removeCompanyMember(User $user): void
    {
        if ($user->current_company_id === $this->id) {
            $user->forceFill([
                'current_company_id' => null,
            ])->save();
        }
        $this->onlyCompanyMembers()->detach($user);
    }

    /**
     * Get all of the pending user invitations for the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyInvitations()
    {
        return $this->hasMany(CompanyInvitation::class);
    }
    
	/*
    |--------------------------------------------------------------------------
	| COMPANY BRANCH SECTION
	| Contain functions related to company branchs.
	| Related model: \App\Models\Company\CompanyBranch
    |--------------------------------------------------------------------------
	*/

    /**
     * Retorna todas as filiais pertencentes à empresa
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedCompanyBranchs(): HasMany
    {
        return $this->hasMany(CompanyBranch::class);
    }
}
