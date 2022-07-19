<?php declare(strict_types=1);

namespace App\Vehicle\Actions\GetVehicleLocalization;

use App\Vehicle\Models\VehicleLocalization;
use App\Vehicle\Actions\VehicleLocalizationDto;
use App\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalizationCommand;

final class GetVehicleLocalizationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalizationCommand $command
     * @return \App\Vehicle\Actions\VehicleLocalizationDto
     */
    public function handle(GetVehicleLocalizationCommand $command): VehicleLocalizationDto
    {
		$localization = VehicleLocalization::findVehicleLocalizationByIdOrFail($command->id);
		return VehicleLocalizationDto::fromModel($localization);
    }
}
