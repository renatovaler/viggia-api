<?php

namespace Database\Factories\License;

use App\User\Models\User;
use App\Company\Models\Company;
use App\License\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\License\Models\License>
 */
class LicenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = License::class;
	
    /**
     * Company Branch
     *
     * @var Illuminate\Database\Eloquent\Collection|null
     */
	protected $company = null;
	
    /**
     * Company
     *
     * @var Illuminate\Database\Eloquent\Collection|null
     */
	protected $companyBranch = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
		$companyId = $this->companyOrNull();
		$companyBranchId = $this->companyBranchOrNull();
		$this->company = null;
		$this->companyBranch = null;
        return [
			'slave_key' => $this->faker->uuid,
			'user_id' => User::all()->random()->id,
			'company_id' => $companyId,
			'company_branch_id' => $companyBranchId,
			'runtime_key_used_to_activate' => $this->faker->uuid,
			'token_used_to_activate' => $this->faker->uuid
        ];
    }
	
    /*
    * Define se esse registro terá empresa registrada setada ou não
    *
    * @return null|int
    */
    protected function companyOrNull(): null|int
    {
        if (rand(0, 1)) { $this->company = Company::all()->random(); }
		return $this->company->id;
    }
	
    /*
    * Define se esse registro terá empresa registrada setada ou não
    *
    * @return null|int
    */
    protected function companyBranchOrNull(): null|int
    {
		if( is_null($this->company) ) {
			return null;
		} else {
			if ( rand(0, 1) ) {
				return null;
			} else {
				return $this->company->ownedCompanyBranchs()->random()->id;
			}
		}
    }
}
