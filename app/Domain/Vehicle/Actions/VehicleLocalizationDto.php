<?php declare(strict_types=1);

namespace App\Domain\Vehicle\Actions;

use App\Domain\Vehicle\Models\VehicleLocalization;

final class VehicleLocalizationDto
{
    /**
     * Método construtor da classe
     *
     * @param readonly int $id
     * @param readonly string $licensePlate
     * @param readonly float $localizationLatitude
     * @param readonly float $localizationLongitude
     * @param readonly string $localizedAt
     *
     * @return void (implicit)
     */
    public function __construct(
        public readonly int $id,
        public readonly string $licensePlate,
        public readonly float $localizationLatitude,
        public readonly float $localizationLongitude,
        public readonly string $localizedAt
    ) {}

    public static function fromModel(VehicleLocalization $localization): self
    {
        return new self(
            $localization->id,
            $localization->license_plate,
            floatval($localization->localization_latitude),
            floatval($localization->localization_longitude),
            ($localization->localized_at)->format('Y-m-d H:i:s')
        );
    }
}
