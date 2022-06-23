<?php declare(strict_types=1);

namespace App\Domain\Company\Models;

use App\Domain\User\Models\User;
use App\Domain\Company\Models\CompanyMember;
use App\Domain\Company\Models\CompanyBranch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
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
     * @return void
     */
    protected static function newFactory()
    {
        return CompanyFactory::new();
    }

    /**
     * Retorna dados do usuário que é proprietário da empresa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * Retorna todos os usuários que são membros da empresa, INCLUSIVE o proprietário
     * Não inclui dados de membros das filiais, apenas da empresa matriz.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function companyMembersAndOwner(): Collection
    {
        return $this->onlyCompanyMembers->merge([$this->companyOwner]);
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function companyMemberById($companyMemberId): Collection
    {
        return $this->companyMembersAndOwner()->where('id', $companyMemberId)->firstOrFail();
    }
	
    /**
     * Remove membro da empresa.
     *
     * @param  \App\Domain\User\Models\User  $user
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
