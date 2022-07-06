<?php declare(strict_types=1);

namespace App\Domain\Vehicle\Actions\GetVehicleLocalization;

use App\Domain\Vehicle\Models\VehicleLocalization;
use App\Domain\Vehicle\Actions\VehicleLocalizationDto;
use App\Domain\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalizationCommand;

final class GetVehicleLocalizationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalizationCommand $command
     * @return \App\Domain\Vehicle\Actions\VehicleLocalizationDto
     */
    public function handle(GetVehicleLocalizationCommand $command): VehicleLocalizationDto
    {
		$localization = VehicleLocalization::findVehicleLocalizationByIdOrFail($command->id);
		return VehicleLocalizationDto::fromModel($localization);
    }
}
