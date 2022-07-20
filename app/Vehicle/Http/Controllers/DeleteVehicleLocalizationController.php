<?php declare(strict_types=1);

namespace App\Vehicle\Http\Controllers;

use Illuminate\Http\Response;
use App\Structure\Http\Controllers\Controller;

use App\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalization;

class DeleteVehicleLocalizationController extends Controller
{
    /**
	 * Delete vehicle localization record by id
     *
     * @param  int $vehicleId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $vehicleId): Response
    {
        dispatch_sync( new DeleteVehicleLocalization($vehicleId) );
        return response()->noContent();
    }
}
