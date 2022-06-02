<?php

use Illuminate\Support\Facades\Route;

use App\UI\Http\Company\Controllers\GetCurrentCompanyInformationController;
use App\UI\Http\Company\Controllers\UpdateCurrentCompanyInformationController;

/*
|--------------------------------------------------------------------------
| API Routes - Company Domain
|--------------------------------------------------------------------------
|
| As rotas de API do domínio "Company" devem ser registradas aqui.
|
| As rotas devem seguir o seguinte padrão:
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
    // Company group
	Route::group(['prefix' => 'company'], function () {
		// Current company route group
		Route::group(['prefix' => 'current'], function () {
			/**
			 * Get current company information
			 *
			 * @method  GET
			 * @route   domain.example/company/current/profile
			 * @name    company.current.profile.show
			 */
			Route::get('profile', [GetCurrentCompanyInformationController::class, '__invoke'])
			->name('company.current.profile.show');

			/**
			 * Update current company information
			 *
			 * @method  PUT
			 * @route   domain.example/company/current/profile
			 * @name    company.current.profile.update
			 */
			Route::put('profile', [UpdateCurrentCompanyInformationController::class, '__invoke'])
			->name('company.current.profile.update');
		});
	});
});
