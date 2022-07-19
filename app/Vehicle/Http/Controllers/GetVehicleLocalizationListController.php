<?php declare(strict_types=1);

namespace App\Vehicle\Http\Controllers;

use App\Structure\Http\Controllers\Controller;
use App\Vehicle\Http\Resources\VehicleLocalizationCollection;
use App\Vehicle\Actions\GetVehicleLocalizationList\GetVehicleLocalizationListCommand;

class GetVehicleLocalizationListController extends Controller
{
    /**
     * Get list of vehicle localizations
     *
     * @return \App\Vehicle\Http\Resources\VehicleLocalizationCollection
     */
    public function __invoke(): VehicleLocalizationCollection
    {
        $localizations = dispatch_sync( new GetVehicleLocalizationListCommand() );
        return (new VehicleLocalizationCollection($localizations));
    }
}
