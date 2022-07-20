<?php declare(strict_types=1);

namespace App\Vehicle\Http\Controllers;

use App\Vehicle\Http\Resources\VehicleLocalizationResource;
use App\Structure\Http\Controllers\Controller;
use App\Vehicle\Actions\GetVehicleLocalization\GetVehicleLocalization;

class GetVehicleLocalizationByIdController extends Controller
{
    /**
     * Get vehicle localization by id
     *
     * @param  int $vehicleId
     *
     * @return \App\Vehicle\Http\Resources\VehicleLocalizationResource
     */
    public function __invoke(int $vehicleId): VehicleLocalizationResource
    {
        $localization = dispatch_sync( new GetVehicleLocalization($vehicleId) );
        return ( new VehicleLocalizationResource($localization) );
    }
}
