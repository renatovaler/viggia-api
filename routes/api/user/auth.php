<?php

use Illuminate\Support\Facades\Route;

use App\Domains\User\Http\Auth\Controllers\UpdateAuthenticatedUserPasswordController;
use App\Domains\User\Http\Auth\Controllers\GetAuthenticatedUserProfileInformationController;
use App\Domains\User\Http\Auth\Controllers\UpdateAuthenticatedUserProfileInformationController;

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
		// Get authenticated user profile information
		Route::get('my-profile', [GetAuthenticatedUserProfileInformationController::class, '__invoke'])
		->name('api.users.auth.myprofile.show');

		// Update authenticated user profile information
		Route::put('my-profile', [UpdateAuthenticatedUserProfileInformationController::class, '__invoke'])
		->name('api.users.auth.myprofile.update');

		// Update authenticated user password
		Route::put('my-password', [UpdateAuthenticatedUserPasswordController::class, '__invoke'])
		->name('api.users.auth.mypassword.update');
});
