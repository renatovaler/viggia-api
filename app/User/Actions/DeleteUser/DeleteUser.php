<?php declare(strict_types=1);

namespace App\User\Actions\DeleteUser;

use Illuminate\Foundation\Bus\Dispatchable;

final class DeleteUserCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     *
     * @return void (implicit)
     */
    public function __construct(public readonly int $id) {}
}
