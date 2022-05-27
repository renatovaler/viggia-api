<?php declare(strict_types=1);

namespace Database\Factories\User;

use App\Domains\User\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domains\User\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = now();

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => $now,
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'password_changed_at' => $now
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return mixed
     */
    public function unverified(): mixed
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
