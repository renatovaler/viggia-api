<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\CreateCompanyBranch;

use Illuminate\Foundation\Bus\Dispatchable;

final class CreateCompanyBranchCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $ownerCompanyId
     * @param readonly string $name
     *
     * @return void (implicit)
     */
    public function __construct(
		public readonly int $ownerCompanyId,
		public readonly string $name
	) {}
}
