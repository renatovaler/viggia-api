<?php declare(strict_types=1);

namespace App\Domain\Company\Models;

use App\Domain\User\Models\User;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyBranchMember;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Database\Factories\Company\CompanyBranchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyBranch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_branchs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
		'name'
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
     * @return \Database\Factories\Company\CompanyBranchFactory
     */
    protected static function newFactory(): CompanyBranchFactory
    {
        return CompanyBranchFactory::new();
    }

	/*
    |--------------------------------------------------------------------------
	| COMPANY SECTION
	| Contain functions related to company
	| Related model: \App\Domain\Company\Models\Company
    |--------------------------------------------------------------------------
	*/
	/**
     * Get the owner of the branch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyOwnerOfThisBranch(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
	
	/*
    |--------------------------------------------------------------------------
	| COMPANY BRANCH MEMBER SECTION
	| Contain functions related to company branchs.
	| Related model: \App\Models\Company\CompanyBranchMember
    |--------------------------------------------------------------------------
	*/
    /**
     * Retorna todos os membros da filial.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companyBranchMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, CompanyBranchMember::class)
                        ->withTimestamps()
                        ->as('company_branch_members');
    }

    /**
     * Verifica se o usuário é membro da filial ou não. Busca via ID do usuário.
     *
     * @param  int $userId
     * @return bool
     */
    public function hasCompanyBranchMember(int $userId): bool
    {
        return $this->companyBranchMembers->contains('id', $userId);
    }

    /**
     * Verifica se o usuário é membro da filial ou não. Busca via Email do usuário.
     *
     * @param  string $email
     * @return bool
     */
    public function hasCompanyBranchMemberWithEmail(string $email): bool
    {
        return $this->companyBranchMembers->contains('email', $email);
    }

    /**
     * Remove membro da filial
     *
     * @param  \App\Domain\User\Models\User $user
     * @return void
     */
    public function removeCompanyBranchMember(User $user): void
    {
        if ($user->current_company_branch_id === $this->id) {
            $user->forceFill([
                'current_company_branch_id' => null,
            ])->save();
        }
        $this->companyBranchMembers()->detach($user);
    }
}
