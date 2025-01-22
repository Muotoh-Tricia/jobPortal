<?php

namespace Database\Factories;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employer>
 */
class EmployerFactory extends Factory
{
    protected $model = Employer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'employer'])->id,
            'company_name' => $this->faker->company(),
            'company_description' => $this->faker->paragraph(),
            'industry' => $this->faker->randomElement([
                'Technology', 'Finance', 'Healthcare', 
                'Education', 'Manufacturing', 'Retail'
            ]),
            'location' => $this->faker->city()
        ];
    }
}
