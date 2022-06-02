<?php

namespace Database\Factories\Company;

use App\Domain\User\Models\User;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyMember;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company\CompanyMember>
 */
class CompanyMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyMember::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::all()->random();
        $company = $this->companyWhereUserIsNotAMember($user);

        return [
            'user_id' => $user->id,
            'company_id' => $company->id
        ];
    }

    /*
    * Get company where user is not a member
    *
    * @param Illuminate\Database\Eloquent\Collection $user
    * @return mixed<this, Company>
    */
    protected function companyWhereUserIsNotAMember(Collection $user): mixed
    {
        $company = Company::all()->random();
        return $user->hasCompanyMember($company) ? $company : $this->companyWhereUserIsNotAMember($user);
    }
}
