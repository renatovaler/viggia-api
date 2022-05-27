<?php

use Illuminate\Support\Facades\Route;

use App\Domains\User\Http\Auth\Controllers\GetAuthenticatedUserProfileInformationController;

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
		
        // Get authenticated user profile information
        Route::get('profile', [GetAuthenticatedUserProfileInformationController::class, '__invoke'])
        ->name('api.users.myself.profile.show');
		
	});
});
