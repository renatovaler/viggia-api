<?php

use Illuminate\Support\Facades\Route;

// Myself
use App\UI\MyselfUser\Http\Controllers\UpdateMyselfPasswordController;
use App\UI\MyselfUser\Http\Controllers\GetMyselfProfileInformationController;
use App\UI\MyselfUser\Http\Controllers\UpdateMyselfProfileInformationController;

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'myself'], function () {

	/**
	 * Get authenticated user profile information
	 *
	 * @method  GET
	 * @route   domain.example/myself/profile
	 * @name    users.myself.profile.show
	 */
	Route::get('profile', [GetMyselfProfileInformationController::class, '__invoke'])
	->name('myself.profile.show');

	/**
	 * Update authenticated user profile information
	 *
	 * @method  PUT
	 * @route   domain.example/myself/profile
	 * @name    users.myself.profile.update
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

}); // End middleware and prefix myself route group
