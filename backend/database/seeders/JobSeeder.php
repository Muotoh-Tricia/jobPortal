<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure we have an employer to associate jobs with
        $employer = Employer::first();
        
        if (!$employer) {
            // If no employer exists, create one
            $employerUser = User::create([
                'name' => 'Tech Innovations Inc.',
                'email' => 'hr@techinnovations.com',
                'password' => bcrypt('password123'),
                'role' => 'employer'
            ]);

            $employer = Employer::create([
                'user_id' => $employerUser->id,
                'company_name' => 'Tech Innovations Inc.',
                'company_size' => 'medium',
                'industry' => 'Technology'
            ]);
        }

        // Clear existing jobs to prevent duplicates
        Job::query()->delete();

        // Seed job data
        $jobs = [
            [
                'employer_id' => $employer->id,
                'title' => 'Senior Software Engineer',
                'description' => 'We are looking for an experienced software engineer to join our dynamic team. The ideal candidate will have strong skills in PHP, Laravel, and Vue.js.',
                'location' => 'San Francisco, CA',
                'type' => 'full-time',
                'salary' => 120000.00,
                'application_deadline' => now()->addDays(30),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'employer_id' => $employer->id,
                'title' => 'Frontend Developer',
                'description' => 'Join our frontend team and help build cutting-edge web applications. Strong Vue.js and React skills required.',
                'location' => 'Remote',
                'type' => 'part-time',
                'salary' => 85000.00,
                'application_deadline' => now()->addDays(45),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'employer_id' => $employer->id,
                'title' => 'DevOps Engineer',
                'description' => 'We need a skilled DevOps engineer to manage our cloud infrastructure and deployment pipelines.',
                'location' => 'New York, NY',
                'type' => 'contract',
                'salary' => 150000.00,
                'application_deadline' => now()->addDays(60),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert jobs
        Job::insert($jobs);
    }
}
