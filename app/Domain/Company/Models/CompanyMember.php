<?php declare(strict_types=1);

namespace App\Domain\Company\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

use Database\Factories\Company\CompanyMemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyMember extends Pivot
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_members';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Define custom factory to the model
     *
     * @return \Database\Factories\Company\CompanyMemberFactory
     */
    protected static function newFactory(): CompanyMemberFactory
    {
        return CompanyMemberFactory::new();
    }
}
