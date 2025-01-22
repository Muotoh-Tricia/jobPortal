<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_portals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('employers')->onDelete('cascade');
            $table->string('companyLogo')->nullable();
            $table->string('companyName');
            $table->text('Description');
            $table->string('Address');
            $table->string('Phone');
            $table->string('Email');
            $table->decimal('Salary', 10, 2);
            $table->string('Level')->nullable();
            $table->string('Language')->nullable();
            $table->string('Country')->nullable();
            $table->text('Responsibility')->nullable();
            $table->string('Location')->nullable();
            $table->string('job_type')->nullable();
            $table->date('application_deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_portals');
    }
};
