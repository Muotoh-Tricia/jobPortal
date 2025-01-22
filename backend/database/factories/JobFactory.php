<?php

namespace Database\Factories;

use App\Models\Employer;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employer_id' => Employer::factory()->create()->id,
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(3),
            'requirements' => $this->faker->paragraph(2),
            'salary_range' => $this->faker->randomElement([
                '50,000 - 70,000', 
                '70,000 - 90,000', 
                '90,000 - 120,000', 
                '120,000 - 150,000'
            ]),
            'location' => $this->faker->city(),
            'job_type' => $this->faker->randomElement([
                'full-time', 'part-time', 'contract', 'remote'
            ]),
            'status' => $this->faker->randomElement([
                'active', 'closed', 'draft'
            ])
        ];
    }
}
