<?php

use Illuminate\Support\Facades\Route;

use App\UI\Http\User\Controllers\GetMyselfProfileInformationController;
use App\UI\Http\User\Controllers\UpdateMyselfProfileInformationController;
//use App\UI\Http\User\Controllers\UpdateMyselfPasswordController;

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

    // Myself user group
	Route::group(['prefix' => 'myself'], function () {

        /**
         * Get authenticated user profile information
         *
         * @method  GET
         * @route   domain.example/myself/profile
         * @name    myself.profile.show
         */
        Route::get('profile', [GetMyselfProfileInformationController::class, '__invoke'])
        ->name('myself.profile.show');

        /**
         * Update authenticated user profile information
         *
         * @method  PUT
         * @route   domain.example/myself/profile
         * @name    myself.profile.update
         */
        Route::put('profile', [UpdateMyselfProfileInformationController::class, '__invoke'])
        ->name('myself.profile.update');

        /**
         * Update authenticated user password
         *
         * @method  PUT
         * @route   domain.example/myself/password
         * @name    myself.password.update
         */
        //Route::put('password', [UpdateMyselfPasswordController::class, '__invoke'])
        //->name('myself.password.update');
	});
});
