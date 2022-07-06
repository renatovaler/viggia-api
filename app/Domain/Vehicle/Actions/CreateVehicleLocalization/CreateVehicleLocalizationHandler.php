<?php declare(strict_types=1);

namespace App\Domain\Vehicle\Actions\CreateVehicleLocalization;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Domain\Vehicle\Models\VehicleLocalization;
use App\Domain\Vehicle\Actions\VehicleLocalizationDto;
use App\Domain\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalizationCommand;

final class CreateVehicleLocalizationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalization $command
     * @return VehicleLocalizationDto
     */
    public function handle(CreateVehicleLocalizationCommand $command): VehicleLocalizationDto
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
