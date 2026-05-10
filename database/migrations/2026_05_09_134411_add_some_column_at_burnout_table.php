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
        Schema::table('burnouts', function (Blueprint $table) {
            $table->integer('total')->nullable()->after('achievement_decline');
            $table->string('level')->nullable()->after('total');
            $table->text('description')->nullable()->after('level');
            $table->text('recommendation')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('burnouts', function (Blueprint $table) {
            $table->dropColumn(['total', 'level', 'description', 'recommendation']);
        });
    }
};
