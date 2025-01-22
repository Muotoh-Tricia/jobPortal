<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jobseeker>
 */
class JobseekerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        $skillsByIndustry = [
            'Technology' => ['PHP', 'Laravel', 'Vue.js', 'SQL', 'Python', 'JavaScript', 'React', 'DevOps'],
            'Healthcare' => ['Patient Care', 'Medical Coding', 'Clinical Research', 'Nursing', 'Phlebotomy'],
            'Education' => ['Curriculum Development', 'Classroom Management', 'Educational Technology', 'Public Speaking'],
            'Finance' => ['Accounting', 'Financial Analysis', 'Risk Management', 'Tax Preparation', 'Budget Planning'],
            'Retail' => ['Sales Management', 'Customer Service', 'Merchandising', 'Inventory Control'],
            'Construction' => ['Blueprint Reading', 'Project Management', 'Masonry', 'Welding', 'Electrical Wiring'],
        ];

        // Randomly select an industry and its associated skills
        $industry = $this->faker->randomElement(array_keys($skillsByIndustry));
        $skills = $skillsByIndustry[$industry];

        // Select a random number of skills, ensuring it's within the available range
        $numSkills = rand(3, min(count($skills), 5)); // Ensure we don't exceed the number of available skills
        $selectedSkills = $this->faker->randomElements($skills, $numSkills);

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => '+1' . $this->faker->numerify('###-###-####'),
            'date_of_birth' => $this->faker->date('Y-m-d', '2000-01-01'),
            'address' => $this->faker->streetAddress . ', ' . $this->faker->city,
            'resume' => 'resumes/' . strtolower($this->faker->firstName) . '.pdf', // Example file path
            'skills' => implode(', ', $selectedSkills), // Join skills into a string
            'experience' => "2 years of experience in {$industry}.",
        ];
    }
}
