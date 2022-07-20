<?php declare(strict_types=1);

namespace App\Vehicle\Actions\UpdateVehicleLocalization;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Vehicle\Models\VehicleLocalization;
use App\Vehicle\Actions\VehicleLocalizationDto;
use App\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalization;

final class UpdateVehicleLocalizationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalization $command
     * @return \App\Vehicle\Actions\VehicleLocalizationDto
     */
    public function handle(UpdateVehicleLocalization $command): VehicleLocalizationDto
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
