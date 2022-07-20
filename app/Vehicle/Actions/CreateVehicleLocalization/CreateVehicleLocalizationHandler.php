<?php declare(strict_types=1);

namespace App\Vehicle\Actions\CreateVehicleLocalization;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Vehicle\Models\VehicleLocalization;
use App\Vehicle\Actions\VehicleLocalizationDto;
use App\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalization;

final class CreateVehicleLocalizationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalization $command
     * @return VehicleLocalizationDto
     */
    public function handle(CreateVehicleLocalization $command): VehicleLocalizationDto
    {
        try {
            DB::beginTransaction();
				$localization = VehicleLocalization::create([
					'license_plate' => $command->licensePlate,
					'localization_latitude' => $command->localizationLatitude,
					'localization_longitude' => $command->localizationLongitude,
					'localized_at' => $command->localizedAt
				]);
            DB::commit();
			return VehicleLocalizationDto::fromModel($localization);
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
