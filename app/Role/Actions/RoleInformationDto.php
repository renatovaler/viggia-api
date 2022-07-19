<?php declare(strict_types=1);

namespace App\Role\Actions;

use App\Role\Models\Role;

final class RoleInformationDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly string $name
     * @param readonly string $description
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description
    ) {}

    public static function fromModel(Role $role): self
    {
        return new self(
            $role->id,
            $role->name,
            $role->description
        );
    }
}
