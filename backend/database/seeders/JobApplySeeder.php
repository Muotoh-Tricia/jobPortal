<?php

namespace Database\Seeders;

use App\Models\JobApply;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobapplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        JobApply::factory(30)->create(); // Example: Create 30 applications
    }
}
