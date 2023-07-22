<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['MALE', 'FEMALE']),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'age' => $this->faker->numberBetween(18, 60),
            'photo' => $this->faker->imageUrl(640, 480, 'people', true),
            'team_id' => $this->faker->numberBetween(1, 30),
            'role_id' => $this->faker->numberBetween(1, 100),

        ];
    }
}
