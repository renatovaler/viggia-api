<?php declare(strict_types=1);

namespace App\UI\Vehicle\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;

use App\Domain\Vehicle\Actions\DeleteVehicleLocalization\DeleteVehicleLocalizationCommand;

class DeleteVehicleLocalizationController extends Controller
{
    /**
	 * Delete vehicle localization record by id
     *
     * @param  int $vehicleId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $vehicleId): JsonResponse
    {
        $localization = dispatch_sync(
			new DeleteVehicleLocalizationCommand($vehicleId)
		);
        return response()->noContent();
    }
}
