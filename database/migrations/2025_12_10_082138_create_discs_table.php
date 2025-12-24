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
        Schema::create('discs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_number')->nullable();

            // D1-I1-S1-C1
            $table->decimal('D1', 5, 2)->nullable();
            $table->decimal('I1', 5, 2)->nullable();
            $table->decimal('S1', 5, 2)->nullable();
            $table->decimal('C1', 5, 2)->nullable();

            // D2-I2-S2-C2
            $table->decimal('D2', 5, 2)->nullable();
            $table->decimal('I2', 5, 2)->nullable();
            $table->decimal('S2', 5, 2)->nullable();
            $table->decimal('C2', 5, 2)->nullable();

            // D3-I3-S3-C3
            $table->decimal('D3', 5, 2)->nullable();
            $table->decimal('I3', 5, 2)->nullable();
            $table->decimal('S3', 5, 2)->nullable();
            $table->decimal('C3', 5, 2)->nullable();

            // Interpretations
            $table->string('interpretation1')->nullable();
            $table->string('interpretation2')->nullable();
            $table->string('interpretation3')->nullable();

            $table->string('external_id')->nullable();
            $table->string('source')->default('eksternal');
            $table->timestamps();

            $table->index('employee_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discs');
    }
};
