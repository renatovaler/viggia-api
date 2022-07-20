<?php declare(strict_types=1);

namespace App\Vehicle\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\Vehicle\Http\Resources\VehicleLocalizationResource;
use App\Vehicle\Http\Requests\UpdateVehicleLocalizationRequest;
use App\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalization;

class UpdateVehicleLocalizationController extends Controller
{
    /**
     * Update another user profile information (not myself)
     *
     * @param App\Vehicle\Http\Requests\VehicleLocalizationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateVehicleLocalizationRequest $request): JsonResponse
    {

        $localization = dispatch_sync(new UpdateVehicleLocalization(
            (int) $request->input('id'),
            $request->input('license_plate'),
            floatval( $request->input('localization_latitude') ),
            floatval( $request->input('localization_longitude') ),
            Carbon::parse( $request->input('localized_at') )
        ));
        return ( new VehicleLocalizationResource($localization) )->response($request);
    }
}
