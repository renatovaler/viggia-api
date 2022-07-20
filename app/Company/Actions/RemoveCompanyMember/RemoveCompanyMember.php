<?php declare(strict_types=1);

namespace App\Company\Actions\RemoveCompanyMember;

use Illuminate\Foundation\Bus\Dispatchable;

final class RemoveCompanyMember
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $companyId
     * @param readonly int $userId
     *
     * @return void (implicit)
     */
    public function __construct(
		public readonly int $companyId,
		public readonly int $userId
	) {}
}
