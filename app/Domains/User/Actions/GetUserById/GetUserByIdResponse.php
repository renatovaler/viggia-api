<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUserById;

final class GetUserByIdResponse
{
    /**
     * Método construtor da classe
     *
     * @param readonly string $id
     * @param readonly string $name
     * @param readonly string $email
     * @param readonly string $email_verified_at
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $email_verified_at
    ) {}
}
