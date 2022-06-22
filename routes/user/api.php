<?php

use Illuminate\Support\Facades\Route;

// Myself
use App\UI\User\Http\Controllers\UpdateMyselfPasswordController;
use App\UI\User\Http\Controllers\GetMyselfProfileInformationController;
use App\UI\User\Http\Controllers\UpdateMyselfProfileInformationController;

// Another users
use App\UI\User\Http\Controllers\UpdateUserProfileInformationController;
use App\UI\User\Http\Controllers\GetUserProfileInformationByIdController;

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

	Route::group(['prefix' => 'users'], function () {

        // Myself user group
        Route::group(['prefix' => 'myself'], function () {

            /**
             * Get authenticated user profile information
             *
             * @method  GET
             * @route   domain.example/users/myself/profile
             * @name    users.myself.profile.show
             */
            Route::get('profile', [GetMyselfProfileInformationController::class, '__invoke'])
            ->name('users.myself.profile.show');

            /**
             * Update authenticated user profile information
             *
             * @method  PUT
             * @route   domain.example/users/myself/profile
             * @name    users.myself.profile.update
             */
            Route::put('profile', [UpdateMyselfProfileInformationController::class, '__invoke'])
            ->name('users.myself.profile.update');

            /**
             * Update authenticated user password
             *
             * @method  PUT
             * @route   domain.example/users/myself/password
             * @name    users.myself.password.update
             */
            Route::put('password', [UpdateMyselfPasswordController::class, '__invoke'])
            ->name('users.myself.password.update');
        }); // End prefix "myself" (users/myself) route group

        // Another users group
        Route::group(['prefix' => 'user'], function () {

            /**
             * Get another user profile information by user_id
             *
             * @method  GET
             * @route   domain.example/users/user/{userId}/profile
             * @name    users.user.profile.show
             */
            Route::get('{userId}/profile', [GetUserProfileInformationByIdController::class, '__invoke'])
            ->name('users.user.profile.show');

            /**
             * Update another user profile information
             *
             * @method  PUT
             * @route   domain.example/users/user/{userId}/profile
             * @name    users.user.profile.update
             */
            Route::put('{userId}/profile', [UpdateUserProfileInformationController::class, '__invoke'])
            ->name('users.user.profile.update');
        }); // End prefix "user" (users/user) route group

    }); // End prefix "users" (/users/...) route group

}); // End middleware route group
