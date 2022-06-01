<?php declare(strict_types=1);

namespace App\Domain\User\Actions\UpdateUser;

use App\Domain\User\Models\User;
use Illuminate\Support\Carbon;

final class UserDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly string $name
     * @param readonly string $email
     * @param readonly Carbon|null $emailVerifiedAt
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly Carbon|null $emailVerifiedAt
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
