<?php declare(strict_types=1);

namespace App\Domain\Vehicle\Actions\CreateVehicleLocalization;

use Illuminate\Foundation\Bus\Dispatchable;

final class CreateVehicleLocalizationCommand
{
    use Dispatchable;

    /**
     * Método construtor da classe
     *
     * @param readonly string $licensePlate
     * @param readonly float $localizationLatitude
     * @param readonly float $localizationLongitude
     * @param readonly string $localizedAt
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly string $licensePlate,
        public readonly float $localizationLatitude,
        public readonly float $localizationLongitude,
        public readonly string $localizedAt
	) {}
}
