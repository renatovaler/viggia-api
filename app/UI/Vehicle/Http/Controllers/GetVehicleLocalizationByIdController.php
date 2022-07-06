<?php declare(strict_types=1);

namespace App\UI\Vehicle\Http\Controllers;

use App\UI\Vehicle\Http\Resources\VehicleLocalizationResource;
use App\Structure\Http\Controllers\Controller;
use App\Domain\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalizationCommand;

class GetVehicleLocalizationByIdController extends Controller
{
    /**
     * Get vehicle localization by id
     *
     * @param  int $vehicleId
     *
     * @return \App\UI\Vehicle\Http\Resources\VehicleLocalizationResource
     */
    public function __invoke(int $vehicleId): VehicleLocalizationResource
    {
        $localization = dispatch_sync( new GetVehicleLocalizationCommand($vehicleId) );
        return ( new VehicleLocalizationResource($localization) );
    }
}
