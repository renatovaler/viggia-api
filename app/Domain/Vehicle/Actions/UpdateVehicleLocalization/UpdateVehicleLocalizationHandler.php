<?php declare(strict_types=1);

namespace App\Domain\Vehicle\Actions\UpdateVehicleLocalization;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Domain\Vehicle\Models\VehicleLocalization;
use App\Domain\Vehicle\Actions\VehicleLocalizationDto;
use App\Domain\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalizationCommand;

final class UpdateVehicleLocalizationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalizationCommand $command
     * @return \App\Domain\Vehicle\Actions\VehicleLocalizationDto
     */
    public function handle(UpdateVehicleLocalizationCommand $command): VehicleLocalizationDto
    {
        try {
            DB::beginTransaction();
				$localization = VehicleLocalization::findVehicleLocalizationByIdOrFail($command->id);
                $localization->forceFill([
					'license_plate' => $command->licensePlate,
					'localization_latitude' => $command->localizationLatitude,
					'localization_longitude' => $command->localizationLongitude,
					'localized_at' => $command->localizedAt
                ])->save();
            DB::commit();
			return VehicleLocalizationDto::fromModel($localization);
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
