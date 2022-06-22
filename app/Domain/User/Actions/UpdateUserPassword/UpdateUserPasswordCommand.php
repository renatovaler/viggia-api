<?php declare(strict_types=1);

namespace App\Domain\User\Actions\UpdateUserPassword;

use Illuminate\Foundation\Bus\Dispatchable;

final class UpdateUserPasswordCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly string $password
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $password
    ) {}
}
