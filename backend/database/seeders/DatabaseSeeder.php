<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employer;
use App\Models\JobSeeker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test employer
        $employerUser = User::create([
            'name' => 'John Employer',
            'email' => 'employer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'employer'
        ]);

        Employer::create([
            'user_id' => $employerUser->id,
            'company_name' => 'Tech Solutions Ltd',
            'company_website' => 'https://techsolutions.com',
            'company_description' => 'A leading technology solutions provider',
            'company_location' => 'San Francisco, CA',
            'contact_email' => 'hr@techsolutions.com',
            'contact_phone' => '+1-555-123-4567'
        ]);

        // Create test job seeker
        $jobSeekerUser = User::create([
            'name' => 'Jane Jobseeker',
            'email' => 'jobseeker@example.com',
            'password' => Hash::make('password123'),
            'role' => 'jobseeker'
        ]);

        JobSeeker::create([
            'user_id' => $jobSeekerUser->id,
        ]);

        // Seed jobs
        $this->call(JobSeeder::class);
    }
}
