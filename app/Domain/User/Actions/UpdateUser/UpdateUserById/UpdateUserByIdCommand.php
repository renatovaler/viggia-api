<?php declare(strict_types=1);

namespace App\Domains\User\Actions\UpdateUser\UpdateUserById;

use Illuminate\Foundation\Bus\Dispatchable;

final class UpdateUserByIdCommand
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
