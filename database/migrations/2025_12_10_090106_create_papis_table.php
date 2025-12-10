<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('papis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_number')->nullable();

            // PAPI scales (tinyInteger untuk angka kecil)
            $table->tinyInteger('N')->nullable();
            $table->tinyInteger('G')->nullable();
            $table->tinyInteger('A')->nullable();
            $table->tinyInteger('L')->nullable();
            $table->tinyInteger('P')->nullable();
            $table->tinyInteger('I')->nullable();
            $table->tinyInteger('T')->nullable();
            $table->tinyInteger('V')->nullable();
            $table->tinyInteger('S')->nullable();
            $table->tinyInteger('B')->nullable();
            $table->tinyInteger('O')->nullable();
            $table->tinyInteger('X')->nullable();
            $table->tinyInteger('C')->nullable();
            $table->tinyInteger('D')->nullable();
            $table->tinyInteger('R')->nullable();
            $table->tinyInteger('Z')->nullable();
            $table->tinyInteger('E')->nullable();
            $table->tinyInteger('K')->nullable();
            $table->tinyInteger('F')->nullable();
            $table->tinyInteger('W')->nullable();

            $table->string('external_id')->nullable();
            $table->string('source')->default('eksternal');
            $table->timestamps();

            $table->index('employee_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('papis');
    }
};
