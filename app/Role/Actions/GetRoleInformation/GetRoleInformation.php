<?php declare(strict_types=1);

namespace App\Role\Actions\GetRoleInformation;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetRoleInformation
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $roleId
     *
     * @return void (implicit)
     */
    public function __construct(public readonly int $roleId) {}
}
