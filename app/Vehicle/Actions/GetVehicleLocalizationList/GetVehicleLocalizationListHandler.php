<?php declare(strict_types=1);

namespace App\Vehicle\Actions\GetVehicleLocalizationList;

use Illuminate\Support\Collection;

use App\Vehicle\Models\VehicleLocalization;
use App\Vehicle\Actions\VehicleLocalizationListDto;

final class GetVehicleLocalizationListHandler
{
    /**
     * Executa a ação
     *
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
		$localizations = VehicleLocalization::all();
		return VehicleLocalizationListDto::fromCollection($localizations);
    }
}
