<?php declare(strict_types=1);

namespace App\Vehicle\Actions\GetVehicleLocalization;

use Illuminate\Foundation\Bus\Dispatchable;

final class GetVehicleLocalization
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
