<?php

namespace Database\Factories\Company;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyBranch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company\CompanyBranch>
 */
class CompanyBranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyBranch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'company_id' => Company::all()->random()->id,
            'name' => $this->faker->company
        ];
    }
}
