<?php declare(strict_types=1);

namespace App\Domains\User\Actions\GetUser;

use App\Domains\User\Models\User;
use Illuminate\Support\Carbon;

final class UserDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly string $name
     * @param readonly string $email
     * @param readonly Carbon $emailVerifiedAt
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly Carbon $emailVerifiedAt
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
