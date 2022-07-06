<?php declare(strict_types=1);

namespace App\Domain\Vehicle\Actions\GetVehicleLocalizationList;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Vehicle\Models\VehicleLocalization;
use App\Domain\Vehicle\Actions\VehicleLocalizationListDto;

final class GetVehicleLocalizationListHandler
{
    /**
     * Executa a ação
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function handle(): Collection
    {
		$localizations = VehicleLocalization::all();
		return VehicleLocalizationListDto::fromCollection($localizations);
    }
}
