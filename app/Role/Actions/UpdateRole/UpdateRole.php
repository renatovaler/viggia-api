<?php declare(strict_types=1);

namespace App\Role\Actions\UpdateRole;

use Illuminate\Foundation\Bus\Dispatchable;

final class UpdateRoleCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $roleId
     * @param readonly string $name
     * @param readonly string $description
     *
     * @return void (implicit)
     */
    public function __construct(
		public readonly int $roleId,
		public readonly string $name,
		public readonly string $description,
	) {}
}
