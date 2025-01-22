<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the user already exists to prevent duplicates
        $existingUser = User::where('email', 'test@jobportal.com')->first();
        
        if (!$existingUser) {
            User::create([
                'name' => 'Test User',
                'email' => 'test@jobportal.com',
                'password' => Hash::make('TestPassword123!'),
                'email_verified_at' => now()
            ]);
        }
    }
}
