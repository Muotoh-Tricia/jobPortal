<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employer;
use App\Models\JobPortal;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test employer
        $employer = Employer::create([
            'name' => 'Test Employer',
            'email' => 'employer@test.com',
            'password' => bcrypt('password'),
            'company_name' => 'Test Company',
            'company_description' => 'A test company description',
            'website' => 'https://test.com',
            'phone' => '1234567890',
            'address' => '123 Test St'
        ]);

        // Create some test jobs
        JobPortal::create([
            'employer_id' => $employer->id,
            'companyLogo' => 'https://via.placeholder.com/150',
            'companyName' => 'Test Company',
            'Description' => 'This is a test job description',
            'Address' => '123 Test St',
            'Phone' => '1234567890',
            'Email' => 'jobs@test.com',
            'Salary' => 50000.00,
            'Level' => 'Entry Level',
            'Language' => 'English',
            'Country' => 'United States',
            'Responsibility' => 'Test responsibilities',
            'Location' => 'Remote',
            'job_type' => 'Full-time',
            'application_deadline' => '2025-12-31'
        ]);

        JobPortal::create([
            'employer_id' => $employer->id,
            'companyLogo' => 'https://via.placeholder.com/150',
            'companyName' => 'Test Company',
            'Description' => 'This is another test job description',
            'Address' => '123 Test St',
            'Phone' => '1234567890',
            'Email' => 'jobs@test.com',
            'Salary' => 75000.00,
            'Level' => 'Mid Level',
            'Language' => 'English',
            'Country' => 'United States',
            'Responsibility' => 'Test responsibilities',
            'Location' => 'Hybrid',
            'job_type' => 'Full-time',
            'application_deadline' => '2025-12-31'
        ]);
    }
}
