<?php declare(strict_types=1);

namespace App\Company\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyBranchMember extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_branch_members';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}
