<?php declare(strict_types=1);

namespace App\Domain\Vehicle\Actions;

use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

use App\Domain\Vehicle\Actions\VehicleLocalizationDto;

final class VehicleLocalizationListDto
{
    public static function fromCollection(EloquentCollection $localizations): SupportCollection
    {
        return $localizations->map(function ($localization) {
			return VehicleLocalizationDto::fromModel($localization);
		});
    }
}
