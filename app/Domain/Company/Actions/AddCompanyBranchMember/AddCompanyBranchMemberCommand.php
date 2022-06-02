<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\AddCompanyBranchMember;

use Illuminate\Foundation\Bus\Dispatchable;

final class AddCompanyBranchMemberCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $companyBranchId
     * @param readonly int $userId
     *
     * @return void (implicit)
     */
    public function __construct(
		public readonly int $companyBranchId,
		public readonly int $userId
	) {}
}
