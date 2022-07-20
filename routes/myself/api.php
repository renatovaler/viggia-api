<?php

use Illuminate\Support\Facades\Route;

// Myself
use App\User\Http\Controllers\UpdateMyselfPasswordController;
use App\User\Http\Controllers\GetMyselfProfileInformationController;
use App\User\Http\Controllers\UpdateMyselfProfileInformationController;

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'myself'], function () {
	
	/**
	 * Get authenticated user profile information
	 *
	 * @method  GET
	 * @route   domain.example/users/myself/profile
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
	Route::put('password', [UpdateMyselfPasswordController::class, '__invoke'])
	->name('myself.password.update');

}); // End middleware route group
