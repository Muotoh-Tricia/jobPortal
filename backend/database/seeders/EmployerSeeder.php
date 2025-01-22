<?php

namespace Database\Seeders;

use App\Models\JobPortal;
use App\Models\Employer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Employer::factory(5)
            ->create()
            ->each(function ($employer) {
                $employer->jobs()->saveMany(JobPortal::factory(3)->make()); // Creates 3 jobs for each employer
            });
    }

}
