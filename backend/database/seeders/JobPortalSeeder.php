<?php

namespace Database\Seeders;

use App\Models\JobPortal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        JobPortal::factory(15)->create(); // Example: Create 15 jobs
    }
}
