<?php declare(strict_types=1);

namespace App\Company\Models;

use App\User\Models\User;
//use App\Company\Models\Company;
//use App\Company\Models\CompanyBranchMember;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Database\Factories\Company\CompanyFinancingAgreementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyFinancingAgreement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_financing_agreements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
		'license_plate',
		'is_active',
		'is_wanted'
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
     * @return \Database\Factories\Company\CompanyFinancingAgreementFactory
     */
    protected static function newFactory(): CompanyFinancingAgreementFactory
    {
        return CompanyFinancingAgreementFactory::new();
    }

	/**
     * Get the owner of the financing agreement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyOwnerOfThisContract(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
