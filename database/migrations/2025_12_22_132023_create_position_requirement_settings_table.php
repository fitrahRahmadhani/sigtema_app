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
        Schema::create('position_requirement_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('positions');
            $table->string('position_code');
            $table->string('name');
            $table->string('value');
            $table->timestamps();

            $table->index('position_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_requirement_settings');
    }
};
