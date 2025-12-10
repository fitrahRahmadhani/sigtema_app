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
        Schema::create('mbtis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_number')->nullable();
            $table->string('mbti')->nullable();
            $table->string('external_id')->nullable();
            $table->string('source')->default('eksternal');
            $table->timestamps();

            $table->unique(['source', 'external_id']);
            $table->index('employee_number');
            $table->index('mbti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbtis');
    }
};
