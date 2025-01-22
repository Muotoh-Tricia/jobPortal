<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jobseekers', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique(); // Unique email
            $table->string('phone_number')->nullable(); // Optional phone number
            $table->date('date_of_birth')->nullable(); // Optional date of birth
            $table->string('address')->nullable(); // Optional address
            $table->string('resume')->nullable(); // Path to uploaded resume
            $table->text('skills')->nullable(); // Skills (comma-separated or JSON)
            $table->text('experience')->nullable(); // Job experience details
            $table->timestamps(); // Created_at and Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobseekers');
    }
};
