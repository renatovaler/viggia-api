<?php declare(strict_types=1);

namespace App\Domain\Role\Actions\CreateRole;

use Illuminate\Foundation\Bus\Dispatchable;

final class CreateRoleCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly string $name
     * @param readonly string $description
     *
     * @return void (implicit)
     */
    public function __construct(
		public readonly string $name,
		public readonly string $description,
	) {}
}
