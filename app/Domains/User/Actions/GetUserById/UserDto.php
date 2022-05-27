<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUserById;

final class UserDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly string $id
     * @param readonly string $name
     * @param readonly string $email
     * @param readonly string $emailVerifiedAt
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $emailVerifiedAt
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->name,
            $user->email,
            $user->email_verified_at
        );
    }
}
