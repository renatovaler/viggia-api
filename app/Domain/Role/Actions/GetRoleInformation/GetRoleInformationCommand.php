<?php declare(strict_types=1);

namespace App\Domain\Role\Actions\GetRoleInformation;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetRoleInformationCommand
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
