<?php declare(strict_types=1);

namespace App\Vehicle\Actions\DeleteVehicleLocalization;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Vehicle\Models\VehicleLocalization;
use App\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalizationCommand;

final class DeleteVehicleLocalizationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalizationCommand $command
     * @return void
     */
    public function handle(DeleteVehicleLocalizationCommand $command): void
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
