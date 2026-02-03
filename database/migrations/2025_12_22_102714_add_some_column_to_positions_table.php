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
        Schema::table('positions', function (Blueprint $table) {
            $table->integer('band')->nullable()->after('name');
            $table->string('work_unit')->nullable()->after('band');
            $table->string('group')->nullable()->after('directorate');
            $table->string('komp')->nullable()->after('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('band');
            $table->dropColumn('work_unit');
            $table->dropColumn('group');
            $table->dropColumn('komp');
        });
    }
};
