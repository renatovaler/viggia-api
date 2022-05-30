<?php declare(strict_types=1);

namespace App\Domain\User\Actions\GetUser\GetUserByEmail;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetUserByEmailCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly string $email
     * @return void (implicit)
     */
    public function __construct(public readonly string $email) {}
}
