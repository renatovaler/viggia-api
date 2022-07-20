<?php declare(strict_types=1);

namespace App\User\Actions\UpdateUserPersonalInformation;

use Illuminate\Foundation\Bus\Dispatchable;

final class UpdateUserPersonalInformation
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly string $name
     * @param readonly string $email
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email
    ) {}
}
