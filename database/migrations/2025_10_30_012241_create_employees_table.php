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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('position_id')->constrained('positions');
            $table->string('slug');
            $table->string('employee_number');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('birth_date');
            $table->string('gender');
            $table->string('password');
            $table->string('personal_grade');
            $table->string('hire_date');
            $table->string('profile_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
