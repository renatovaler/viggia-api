<?php declare(strict_types=1);

namespace App\UI\Vehicle\Http\Controllers;

use App\Structure\Http\Controllers\Controller;
use App\UI\Vehicle\Http\Resources\VehicleLocalizationCollection;
use App\Domain\Vehicle\Actions\GetVehicleLocalizationList\GetVehicleLocalizationListCommand;

class GetVehicleLocalizationListController extends Controller
{
    /**
     * Get list of vehicle localizations
     *
     * @return \App\UI\Vehicle\Http\Resources\VehicleLocalizationCollection
     */
    public function __invoke(): VehicleLocalizationCollection
    {
        $localizations = dispatch_sync( new GetVehicleLocalizationListCommand() );
        return (new VehicleLocalizationCollection($localizations));
    }
}
