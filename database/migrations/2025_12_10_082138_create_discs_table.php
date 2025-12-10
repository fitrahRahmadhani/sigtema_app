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
            $table->tinyInteger('D1')->nullable();
            $table->tinyInteger('I1')->nullable();
            $table->tinyInteger('S1')->nullable();
            $table->tinyInteger('C1')->nullable();

            // D2-I2-S2-C2
            $table->tinyInteger('D2')->nullable();
            $table->tinyInteger('I2')->nullable();
            $table->tinyInteger('S2')->nullable();
            $table->tinyInteger('C2')->nullable();

            // D3-I3-S3-C3
            $table->tinyInteger('D3')->nullable();
            $table->tinyInteger('I3')->nullable();
            $table->tinyInteger('S3')->nullable();
            $table->tinyInteger('C3')->nullable();

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
