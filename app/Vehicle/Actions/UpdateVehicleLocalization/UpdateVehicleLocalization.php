<?php declare(strict_types=1);

namespace App\Vehicle\Actions\UpdateVehicleLocalization;

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Bus\Dispatchable;

final class UpdateVehicleLocalization
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly string $licensePlate
     * @param readonly float $localizationLatitude
     * @param readonly float $localizationLongitude
     * @param readonly Carbon $localizedAt
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $licensePlate,
        public readonly float $localizationLatitude,
        public readonly float $localizationLongitude,
        public readonly Carbon $localizedAt
    ) {}
}
