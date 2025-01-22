<?php

namespace Database\Factories;

use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPortal>
 */
class JobPortalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $jobTypes = ['Full-time', 'Part-time', 'Internship', 'Contract'];
        $levels = ['Entry Level', 'Mid Level', 'Senior Level'];
        $languages = ['English', 'Spanish', 'French', 'German'];
        $countries = ['USA', 'UK', 'Canada', 'Australia'];

        return [
            'employer_id' => Employer::factory(),
            'companyLogo' => $this->faker->imageUrl(200, 200, 'business'),
            'companyName' => $this->faker->company,
            'Description' => $this->faker->paragraphs(2, true),
            'Address' => $this->faker->address,
            'Phone' => $this->faker->phoneNumber,
            'Email' => $this->faker->companyEmail,
            'Salary' => $this->faker->numberBetween(50000, 150000),
            'Level' => $this->faker->randomElement($levels),
            'Language' => $this->faker->randomElement($languages),
            'Country' => $this->faker->randomElement($countries),
            'Responsibility' => $this->faker->paragraphs(3, true),
            'Location' => $this->faker->city,
            'job_type' => $this->faker->randomElement($jobTypes),
            'application_deadline' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
