<?php

use Illuminate\Support\Facades\Route;

// Users
use App\UI\Admin\Http\Controllers\User\GetUserListController;
use App\UI\Admin\Http\Controllers\User\GetUserProfileInformationByIdController;
use App\UI\Admin\Http\Controllers\User\UpdateUserProfileInformationController;

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

	Route::group(['prefix' => 'admin/users'], function () {

        Route::group(['prefix' => 'user'], function () {

            /**
             * Get another user profile information by user_id
             *
             * @method  GET
             * @route   domain.example/admin/users/user/{userId}/profile
             * @name    admin.users.user.profile.show
             */
            Route::get('{userId}/profile', [GetUserProfileInformationByIdController::class, '__invoke'])
            ->name('admin.users.user.profile.show');

            /**
             * Update another user profile information
             *
             * @method  PUT
             * @route   domain.example/admin/users/user/{userId}/profile
             * @name    admin.users.user.profile.update
             */
            Route::put('{userId}/profile', [UpdateUserProfileInformationController::class, '__invoke'])
            ->name('admin.users.user.profile.update');
        }); // End prefix "user" (users/user) route group

		/**
		 * Get list of users
		 *
		 * @method  GET
		 * @route   domain.example/admin/users/list
		 * @name    admin.users.list
		 */
		Route::get('list', [GetUserListController::class, '__invoke'])
		->name('admin.users.list');
    }); // End prefix "users" (/users/...) route group

}); // End middleware route group
