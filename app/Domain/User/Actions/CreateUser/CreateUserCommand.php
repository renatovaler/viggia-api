<?php declare(strict_types=1);

namespace App\Domain\User\Actions\CreateUser;

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Bus\Dispatchable;

final class CreateUserCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly string $name
     * @param readonly string $email
     * @param readonly string $password
     * @param readonly null|Carbon $passwordChangedAt
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly null|Carbon $passwordChangedAt
	) {}
}
