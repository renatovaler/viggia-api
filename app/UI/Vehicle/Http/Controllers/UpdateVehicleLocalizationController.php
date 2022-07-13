<?php declare(strict_types=1);

namespace App\UI\Vehicle\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\UI\Vehicle\Http\Resources\VehicleLocalizationResource;
use App\UI\Vehicle\Http\Requests\UpdateVehicleLocalizationRequest;
use App\Domain\Vehicle\Actions\UpdateVehicleLocalization\UpdateVehicleLocalizationCommand;

class UpdateVehicleLocalizationController extends Controller
{
    /**
     * Update another user profile information (not myself)
     *
     * @param App\UI\Vehicle\Http\Requests\VehicleLocalizationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateVehicleLocalizationRequest $request): JsonResponse
    {

        $localization = dispatch_sync(new UpdateVehicleLocalizationCommand(
            (int) $request->input('id'),
            $request->input('license_plate'),
            floatval( $request->input('localization_latitude') ),
            floatval( $request->input('localization_longitude') ),
            Carbon::parse( $request->input('localized_at') )
        ));
        return ( new VehicleLocalizationResource($localization) )->response($request);
    }
}
