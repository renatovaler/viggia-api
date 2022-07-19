<?php declare(strict_types=1);

namespace App\Vehicle\Actions;

use Illuminate\Support\Collection;

use App\Vehicle\Actions\VehicleLocalizationDto;

final class VehicleLocalizationListDto
{
    public static function fromCollection(Collection $localizations): Collection
    {
        return $localizations->map(function ($localization) {
			return VehicleLocalizationDto::fromModel($localization);
		});
    }
}
