<?php

namespace Database\Seeders;

use App\Models\Jobseeker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobseekerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Jobseeker::factory(10)->create();
    }
}
