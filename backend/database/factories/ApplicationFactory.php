<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Job;
use App\Models\JobSeeker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jobSeeker = JobSeeker::factory()->create();
        $job = Job::factory()->create();

        return [
            'job_id' => $job->id,
            'job_seeker_id' => $jobSeeker->id,
            'user_id' => $jobSeeker->user_id,
            'status' => $this->faker->randomElement([
                'pending', 'reviewed', 'accepted', 'rejected'
            ]),
            'cover_letter' => $this->faker->paragraph(2)
        ];
    }
}
