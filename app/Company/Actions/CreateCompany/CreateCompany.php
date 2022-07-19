<?php declare(strict_types=1);

namespace App\Company\Actions\CreateCompany;

use Illuminate\Foundation\Bus\Dispatchable;

final class CreateCompanyCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $ownerUserId
     * @param readonly string $name
     *
     * @return void (implicit)
     */
    public function __construct(
		public readonly int $ownerUserId,
		public readonly string $name
	) {}
}
