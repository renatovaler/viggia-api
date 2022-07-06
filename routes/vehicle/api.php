<?php

use Illuminate\Support\Facades\Route;

// Vehicle Localization
use App\UI\Vehicle\Http\Controllers\CreateVehicleLocalizationController;
use App\UI\Vehicle\Http\Controllers\GetVehicleLocalizationByIdController;
use App\UI\Vehicle\Http\Controllers\GetVehicleLocalizationListController;
use App\UI\Vehicle\Http\Controllers\UpdateVehicleLocalizationController;
use App\UI\Vehicle\Http\Controllers\DeleteVehicleLocalizationController;

/*
|--------------------------------------------------------------------------
| API Routes - User Domain
|--------------------------------------------------------------------------
|
| As rotas de API do domínio "User" devem ser registradas aqui.
|
| Com exceção das rotas relativas ao usuário logado, as demais devem seguir o seguinte padrão:
|
|		Verb		|				URI								|	Action	|		Route Name
|		GET			|	/photos/{photo}/comments					|	index	|	photos.comments.index
|		GET			|	/photos/{photo}/comments/create				|	create	|	photos.comments.create
|		POST		|	/photos/{photo}/comments					|	store	|	photos.comments.store
|		GET			|	/photos/{photo}/comments/{comment}			|	show	|	photos.comments.show
|		GET			|	/photos/{photo}/comments/{comment}/edit		|	edit	|	photos.comments.edit
|		PUT/PATCH	|	/photos/{photo}/comments/{comment}			|	update	|	photos.comments.update
|		DELETE		|	/photos/{photo}/comments/{comment}			|	destroy	|	photos.comments.destroy
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {

	Route::group(['prefix' => 'vehicle'], function () {

        Route::group(['prefix' => 'localizations'], function () {

			/**
			 * Get list of vehicle localizations
			 *
			 * @method  GET
			 * @route   domain.example/vehicle/localizations
			 * @name    vehicle.localizations.index
			 */
			Route::get('', [GetVehicleLocalizationListController::class, '__invoke'])
			->name('vehicle.localizations.index');

            /**
             * Create a new vehicle localization information
             *
             * @method  POST
			 * @route   domain.example/vehicle/localizations
			 * @name    vehicle.localizations.store
             */
            Route::post('', [CreateVehicleLocalizationController::class, '__invoke'])
            ->name('vehicle.localizations.store');
			
            /**
             * Get vehicle localization information by id
             *
             * @method  GET
             * @route   domain.example/vehicle/localizations/{vehicleId}
             * @name    vehicle.localizations.localization.show
             */
            Route::get('{vehicleId}', [GetVehicleLocalizationByIdController::class, '__invoke'])
            ->name('vehicle.localizations.localization.show');

            /**
             * Update vehicle localization information
             *
             * @method  PUT
             * @route   domain.example/vehicle/localizations/{vehicleId}
             * @name    vehicle.localizations.localization.update
             */
            Route::put('{vehicleId}', [UpdateVehicleLocalizationController::class, '__invoke'])
            ->name('vehicle.localizations.localization.update');

            /**
             * Delete vehicle localization record by id
             *
             * @method  DELETE
             * @route   domain.example/vehicle/localizations/{vehicleId}
             * @name    vehicle.localizations.localization.destroy
             */
            Route::delete('{vehicleId}', [DeleteVehicleLocalizationController::class, '__invoke'])
            ->name('vehicle.localizations.localization.destroy');
			
        }); // End prefix "localizations" (vehicle/localizations) route group
		
    }); // End prefix "vehicle" (/vehicle/...) route group

}); // End middleware route group
