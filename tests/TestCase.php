<?php

namespace Tests;

use App\User\Models\User;
use App\Company\Models\Company;
use App\Vehicle\Models\VehicleLocalization;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

	const VALID_PARAM = true;
	const INVALID_PARAM = false;
	
	const NOT_AUTHENTICATED = false;
	const AUTHENTICATED = true;
	
	const COMMON_USER = true;
	const ADMIN_USER = false;
	
	const COMPANY_MEMBER = true;
	const NOT_COMPANY_MEMBER = false;

	/*
	* Latitude and longitude points
	*/
    public array $localizationPoints = [
        [-20.758829008747448, -51.72219608974641],
        [-20.79286960324003, -51.66330042199748],
        [-20.793025515627804, -51.661215740668275],
        [-20.791310470501752, -51.66029848088343],
        [-20.79474054126021, -51.663133647491144],
        [-20.81348127894471, -51.68282255695913],
        [-20.795013319576437, -51.69095104302494],
        [-20.793485409106626, -51.69381119258655],
        [-20.788762678928194, -51.70168589008087],
        [-20.784248166337647, -51.70031153249932],
        [-20.78358834147512, -51.70005151899171],
        [-20.78188667502405, -51.707554768491],
        [-20.78535944332819, -51.70361741974385],
        [-20.794318816704465, -51.70361742002449],
        [-20.792895075838356, -51.678878982484086],
        [-20.78199085933113, -51.669518493009704],
        [-20.79074207640111, -51.6644668002775],
        [-20.792756173575857, -51.66220096750791],
        [-20.791228240249445, -51.66628689545308],
        [-20.79136714391842, -51.670001375403224],
        [-20.801228977710903, -51.67107857452488],
    ];

    protected function setUp(): void
    {
       parent::setUp();
       // set your headers here
       $this->withHeaders(['Accept' => 'application/json']);

	   $this->createDefaultRoles();
    }

	/*
	* Create default roles for tests
	*
	* @return void
	*/
	public function createDefaultRoles(): void
	{
        $userSystemRoles = config('roles.all_user_system_roles');
        $allUserCompanyRoles = config('roles.all_user_company_roles');
        $allUserCompanyBranchRoles = config('roles.all_user_company_branch_roles');

		$roles = collect([])
				->merge($userSystemRoles)
				->merge($allUserCompanyRoles)
				->merge($allUserCompanyBranchRoles);		

        foreach($roles as $key => $role) {
            \App\Role\Models\Role::create([
                'name' => $role,
                'description' => 'Default '. $role
            ]);
        }
	}

	/*
	* Create common user for test
	*
	* @return \App\User\Models\User
	*/
	public function createCommonUser(): User
	{
        $user = User::factory()->create();
        $user->password_changed_at = now();
        $user->save();
		
        return $user;
	}

	/*
	* Create admin and super_admin user for test
	*
	* @return \App\User\Models\User
	*/
	public function createAdminUser(): User
	{
        $user = User::factory()->create();
        $user->password_changed_at = now();
        $user->addRoleToUserByName('super_admin');
        $user->addRoleToUserByName('admin');
        $user->save();
        return $user;
	}

	/*
	* Create users for test
	*
	* @param int $amount
	* @return void
	*/
	public function createUsers(int $amount = 15): void
	{
        User::factory()->count($amount)->create();
	}

	/*
	* Create single vehicle localization for test
	*
	* @return \App\Vehicle\Models\VehicleLocalization
	*/
	public function createVehicleLocalization(): VehicleLocalization
	{
        $localization = VehicleLocalization::factory()->create();
        return $localization;
	}

	/*
	* Create vehicle localizations for test
	*
	* @param int $amount
	* @return void
	*/
	public function createVehicleLocalizations(int $amount = 15): void
	{
        VehicleLocalization::factory()->count($amount)->create();
	}

	/*
	* Get aleatory localization points
	* @return array
	*/
	public function getLocalizationPoints(): array
	{
		return $this->localizationPoints[array_rand($this->localizationPoints, 1)];
	}

	/*
	* Create single company for test
	*
	* @param int $ownerId
	* @return \App\Company\Models\Company
	*/
	public function createCompany(int $ownerId): Company
	{
        $company = Company::create([
            'user_id' => $ownerId,
            'name' => $this->faker->company
		]);
        return $company;
	}

	/*
	* Create companies for test
	*
	* @param int $ownerId
	* @param int $amount
	* @return void
	*/
	public function createCompanies(int $ownerId, int $amount = 15): void
	{
		for ($i = 1; $i <= $amount; $i++) {
			$company = Company::create([
				'user_id' => $ownerId,
				'name' => $this->faker->company
			]);
		}
	}
}
