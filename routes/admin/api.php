<?php

use Illuminate\Support\Facades\Route;

// Users
use App\Admin\Http\Controllers\User\CreateUserController;
use App\Admin\Http\Controllers\User\DeleteUserController;
use App\Admin\Http\Controllers\User\GetUserListController;
use App\Admin\Http\Controllers\User\GetUserProfileInformationByIdController;
use App\Admin\Http\Controllers\User\UpdateUserProfileInformationController;
use App\Admin\Http\Controllers\User\UpdateUserPasswordController;

// Roles
use App\Admin\Http\Controllers\Role\CreateRoleController;
use App\Admin\Http\Controllers\Role\DeleteRoleController;
use App\Admin\Http\Controllers\Role\GetRoleListController;
use App\Admin\Http\Controllers\Role\GetRoleInformationByIdController;
use App\Admin\Http\Controllers\Role\UpdateRoleInformationController;




Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'users'], function () {

	/*
	|--------------------------------------------------------------------------
	| Admin - USERS MODULE
	|--------------------------------------------------------------------------
	*/
	Route::group(['prefix' => 'users'], function () {
		
		/**
		 * Create user
		 *
		 * @method  POST
		 * @route   domain.example/admin/users/create
		 * @name    admin.users.create
		 */
		Route::post('/create', [CreateUserController::class, '__invoke'])
		->name('admin.users.store');

		/**
		 * Delete user record by id
		 *
		 * @method  DELETE
		 * @route   domain.example/admin/users/{userId}
		 * @name    admin.users.destroy
		 */
		Route::delete('{userId}', [DeleteUserController::class, '__invoke'])
		->where('userId', '[0-9]+')
		->name('admin.users.destroy');
		/**
		 * Get another user profile information by user_id
		 *
		 * @method  GET
		 * @route   domain.example/admin/users/{userId}/profile
		 * @name    admin.users.profile.show
		 */
		Route::get('{userId}/profile', [GetUserProfileInformationByIdController::class, '__invoke'])
		->where('userId', '[0-9]+')
		->name('admin.users.profile.show');

		/**
		 * Update another user profile information
		 *
		 * @method  PUT
		 * @route   domain.example/admin/users/{userId}/profile
		 * @name    admin.users.profile.update
		 */
		Route::put('{userId}/profile', [UpdateUserProfileInformationController::class, '__invoke'])
		->where('userId', '[0-9]+')
		->name('admin.users.profile.update');

		/**
		 * Update authenticated user password
		 *
		 * @method  PUT
		 * @route   domain.example/admin/users/{userId}/password
		 * @name    admin.users.password.update
		 */
		Route::put('{userId}/password', [UpdateUserPasswordController::class, '__invoke'])
		->name('admin.users.password.update');

		/**
		 * Get list of users
		 *
		 * @method  GET
		 * @route   domain.example/admin/users
		 * @name    admin.users.index
		 */
		Route::get('', [GetUserListController::class, '__invoke'])
		->name('admin.users.index');

	}); // End prefix "users" (/users/...) route group
	
	/*
	|--------------------------------------------------------------------------
	| Admin - ROLES MODULE
	|--------------------------------------------------------------------------
	*/
	Route::group(['prefix' => 'roles'], function () {
		
		/**
		 * Create new role
		 *
		 * @method  POST
		 * @route   domain.example/admin/roles/create
		 * @name    admin.roles.create
		 */
		Route::post('/create', [CreateRoleController::class, '__invoke'])
		->name('admin.roles.store');

		/**
		 * Delete role record by id
		 *
		 * @method  DELETE
		 * @route   domain.example/admin/roles/{roleId}
		 * @name    admin.roles.destroy
		 */
		Route::delete('{roleId}', [DeleteRoleController::class, '__invoke'])
		->where('roleId', '[0-9]+')
		->name('admin.roles.destroy');
		
		/**
		 * Get role information by id
		 *
		 * @method  GET
		 * @route   domain.example/admin/roles/{roleId}
		 * @name    admin.roles.show
		 */
		Route::get('{roleId}', [GetRoleInformationByIdController::class, '__invoke'])
		->where('roleId', '[0-9]+')
		->name('admin.roles.show');

		/**
		 * Update role information
		 *
		 * @method  PUT
		 * @route   domain.example/admin/roles/{roleId}
		 * @name    admin.roles.update
		 */
		Route::put('{roleId}', [UpdateRoleInformationController::class, '__invoke'])
		->where('roleId', '[0-9]+')
		->name('admin.roles.update');

		/**
		 * Get list of roles
		 *
		 * @method  GET
		 * @route   domain.example/admin/roles
		 * @name    admin.roles.index
		 */
		Route::get('', [GetRoleListController::class, '__invoke'])
		->name('admin.roles.index');
		
	}); // End prefix "roles" (/roles/...) route group
	
}); // End middleware route group
