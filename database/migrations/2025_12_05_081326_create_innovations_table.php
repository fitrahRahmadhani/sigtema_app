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
        Schema::create('innovations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('employee_number')->nullable();
            $table->string('title');
            $table->string('team')->nullable();
            $table->string('theme')->nullable();
            $table->string('event')->nullable();
            $table->string('category')->nullable();
            $table->string('replication')->nullable();
            $table->string('status')->nullable();
            $table->string('achievement')->nullable();
            $table->string('source')->default('eksternal')->nullable();
            $table->string('external_id')->nullable();
            $table->timestamps();

            $table->index('employee_number');
            $table->index('source');
            $table->index(['source', 'external_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('innovations');
    }
};
