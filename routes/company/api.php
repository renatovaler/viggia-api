<?php

use Illuminate\Support\Facades\Route;

// Company
use App\UI\Company\Http\Controllers\CreateCompanyController;
use App\UI\Company\Http\Controllers\UpdateCompanyInformationController;
use App\UI\Company\Http\Controllers\DeleteCompanyController;
use App\UI\Company\Http\Controllers\GetCurrentCompanyInformationController;
use App\UI\Company\Http\Controllers\GetCurrentUserCompanyListController;
use App\UI\Company\Http\Controllers\SwitchCompanyController;

// Company Member
use App\UI\Company\Http\Controllers\Member\AddCompanyMemberController;
use App\UI\Company\Http\Controllers\Member\RemoveCompanyMemberController;
use App\UI\Company\Http\Controllers\Member\GetCompanyMemberInformationController;
use App\UI\Company\Http\Controllers\Member\GetCurrentCompanyMemberListController;




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
	Route::group(['prefix' => 'companies'], function () {

		// Current company route group
		Route::group(['prefix' => 'current-company'], function () {

			/**
			 * Get current company information
			 *
			 * @method  GET
			 * @route   domain.example/companies/current-company/profile
			 * @name    companies.current-company.profile.show
			 */
			Route::get('profile', [GetCurrentCompanyInformationController::class, '__invoke'])
			->name('companies.current-company.profile.show');

			/**
			 * Update current company information
			 *
			 * @method  PUT
			 * @route   domain.example/companies/current-company/profile
			 * @name    companies.current-company.profile.update
			 */
			Route::put('profile', [UpdateCompanyInformationController::class, '__invoke'])
			->name('companies.current-company.profile.update');

			// Current company members route group
			Route::group(['prefix' => 'members'], function () {
				
				/**
				 * Get company member profile information by companyMemberId
				 *
				 * @method  GET
				 * @route   domain.example/companies/current/members/{companyMemberId}
				 * @name    companies.current.members.member.profile.show
				 */
				Route::get('{companyMemberId}', [GetCompanyMemberInformationController::class, '__invoke'])
				->name('companies.current-company.members.profile.show');
				
				/**
				 * Add new member to the current company
				 *
				 * @method  post
				 * @route   domain.example/companies/current-company/members/add-member
				 * @name    companies.current-company.members.add-member
				 */
				Route::post('add-member', [AddCompanyMemberController::class, '__invoke'])
				->name('companies.current-company.members.add-member');

				/**
				 * Remove member to the current company
				 *
				 * @method  DELETE
				 * @route   domain.example/companies/current-company/members/remove-member/{companyMemberId}
				 * @name    companies.current-company.members.member.remove
				 */
				Route::delete('remove-member/{companyMemberId}', [RemoveCompanyMemberController::class, '__invoke'])
				->name('companies.current-company.members.remove-member');
				
				/**
				 * Get current list of company members
				 *
				 * @method  GET
				 * @route   domain.example/companies/current-company/members
				 * @name    companies.current-company.members.index
				 */
				Route::get('', [GetCurrentCompanyMemberListController::class, '__invoke'])
				->name('companies.current-company.members.index');
				
			}); // End current company members route group

		}); //End "current" company route group

		/**
		 * Switch company
		 *
		 * @method  PUT
		 * @route   domain.example/companies/switch
		 * @name    companies.switch
		 */
		Route::put('switch', [SwitchCompanyController::class, '__invoke'])
		->name('companies.switch');

		/**
		 * Creates a new company
		 *
		 * @method  POST
		 * @route   domain.example/companies/create
		 * @name    companies.create
		 */
		Route::post('create', [CreateCompanyController::class, '__invoke'])
		->name('companies.create');

		/**
		 * Get company list of current user
		 *
		 * @method  GET
		 * @route   domain.example/companies/current-user
		 * @name    companies.myself.index
		 */
		Route::get('current-user', [GetCurrentUserCompanyListController::class, '__invoke'])
		->name('companies.current-user.index');


	}); //End company route group
});
