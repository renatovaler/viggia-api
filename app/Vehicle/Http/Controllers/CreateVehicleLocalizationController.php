<?php declare(strict_types=1);

namespace App\Vehicle\Http\Controllers;

use App\Structure\Http\Controllers\Controller;
use App\Vehicle\Http\Resources\VehicleLocalizationResource;
use App\Vehicle\Http\Requests\CreateVehicleLocalizationRequest;
use App\Vehicle\Actions\CreateVehicleLocalization\CreateVehicleLocalization;

use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;

class CreateVehicleLocalizationController extends Controller
{
    /**
     * Create vehicle localization
     *
     * @param App\Vehicle\Http\Requests\CreateVehicleLocalizationRequest $request
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
			new CreateVehicleLocalization(
				$licensePlate,
				$localizationLatitude,
				$localizationLongitude,
				$localizedAt
			)
		);

        return (new VehicleLocalizationResource($localization))->response($request);
    }
}
