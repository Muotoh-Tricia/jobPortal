<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\Models\JobPortal;
use app\Models\Jobseeker;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jobapply>
 */
class JobApplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'coverLetter' => fake()->paragraph(4),
            'resume' => fake()->url(),
            'jobPortal_id' => JobPortal::inRandomOrder()->first()->id,
            'jobseeker_id' => Jobseeker::inRandomOrder()->first()->id,

        ];
    }
}
