<?php

namespace Database\Factories;

use App\Models\JobSeeker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobSeeker>
 */
class JobSeekerFactory extends Factory
{
    protected $model = JobSeeker::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'jobseeker'])->id,
            'resume_path' => $this->faker->optional()->url(),
            'skills' => implode(', ', $this->faker->randomElements([
                'Python', 'JavaScript', 'React', 'Node.js', 
                'Java', 'C++', 'PHP', 'SQL', 'Docker', 'AWS'
            ], $this->faker->numberBetween(2, 5))),
            'experience_years' => $this->faker->numberBetween(0, 15),
            'education_level' => $this->faker->randomElement([
                'High School', 'Bachelor\'s', 'Master\'s', 'PhD', 'Associate Degree'
            ])
        ];
    }
}
