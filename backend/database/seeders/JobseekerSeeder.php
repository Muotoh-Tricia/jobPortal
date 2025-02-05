<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JobSeeker;
use Illuminate\Support\Facades\Hash;

class JobSeekerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure we have users to associate with job seekers
        $users = User::where('role', 'jobseeker')->get();

        if ($users->isEmpty()) {
            // If no job seeker users exist, create some
            $user1 = User::create([
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'role' => 'jobseeker'
            ]);

            $user2 = User::create([
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password456'),
                'role' => 'jobseeker'
            ]);

            $users = collect([$user1, $user2]);
        }

        // Create job seekers for these users
        $users->each(function ($user) {
            $nameParts = explode(' ', $user->name);
            $firstName = $nameParts[0];
            $lastName = count($nameParts) > 1 ? $nameParts[1] : null;

            JobSeeker::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => fake()->phoneNumber(),
                'resume' => 'Sample resume content for ' . $user->name,
                'skills' => json_encode(['PHP', 'Laravel', 'Vue.js', 'JavaScript']),
                'experience' => json_encode([
                    [
                        'company' => 'Tech Solutions Inc.',
                        'position' => 'Software Developer',
                        'duration' => '2020-2023'
                    ]
                ]),
                'education' => json_encode([
                    [
                        'institution' => 'University of Technology',
                        'degree' => 'Bachelor of Computer Science',
                        'graduation_year' => 2020
                    ]
                ])
            ]);
        });
    }
}
