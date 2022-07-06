<?php declare(strict_types=1);

namespace App\UI\Vehicle\Http\Controllers;

use App\Structure\Http\Controllers\Controller;
use App\UI\Vehicle\Http\Resources\VehicleLocalizationResource;
use App\UI\Vehicle\Http\Requests\CreateVehicleLocalizationRequest;
use App\Domain\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalizationCommand;

use Illuminate\Http\JsonResponse;

class CreateVehicleLocalizationController extends Controller
{
    /**
     * Create vehicle localization
     *
     * @param App\UI\Vehicle\Http\Requests\CreateVehicleLocalizationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreateVehicleLocalizationRequest $request): JsonResponse
    {
        $licensePlate = $request->input('license_plate');
        $localizationLatitude = $request->input('localization_latitude');
        $localizationLongitude = $request->input('localization_longitude');
        $localizedAt = $request->input('localized_at');
		
        $localization = dispatch_sync( 
			new CreateVehicleLocalizationCommand(
				$licensePlate,
				$localizationLatitude,
				$localizationLongitude,
				$localizedAt
			) 
		);
        return (new VehicleLocalizationResource($localization))->response($request);
    }
}
