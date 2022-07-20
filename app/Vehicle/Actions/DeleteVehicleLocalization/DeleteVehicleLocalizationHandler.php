<?php declare(strict_types=1);

namespace App\Vehicle\Actions\DeleteVehicleLocalization;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Vehicle\Models\VehicleLocalization;
use App\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalization;

final class DeleteVehicleLocalizationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalization $command
     * @return void
     */
    public function handle(DeleteVehicleLocalization $command): void
    {
        try {
            DB::beginTransaction();
                VehicleLocalization::removeVehicleLocalization($command->id);
            DB::commit();
		} catch(Exception $e) {
			DB::rollBack();
			throw new Exception( $e->getMessage(), $e->getCode() );
        }
    }
}
