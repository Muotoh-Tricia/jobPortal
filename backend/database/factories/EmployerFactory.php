<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employer>
 */
class EmployerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $industries = ['Technology', 'Healthcare', 'Education', 'Finance', 'Retail'];

        return [
            'company_name' => $this->faker->unique()->company,
            'description' => 'We are a reputed company specializing in ' . $this->faker->randomElement($industries),
            'industry' => $this->faker->randomElement($industries),
            'number_of_staff' => $this->faker->numberBetween(5, 500),
            'website' => 'https://' . strtolower($this->faker->domainWord) . '.com',
            'phone_number' => '+1' . $this->faker->numerify('###-###-####'),
            'email' => strtolower($this->faker->companyEmail),
        ];
    }
}
