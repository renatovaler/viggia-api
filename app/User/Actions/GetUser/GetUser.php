<?php declare(strict_types=1);

namespace App\User\Actions\GetUser;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetUser
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
