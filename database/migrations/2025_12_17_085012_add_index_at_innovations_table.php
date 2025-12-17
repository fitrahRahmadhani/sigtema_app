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
        Schema::table('innovations', function (Blueprint $table) {
            // Tambahkan unique constraint untuk mencegah duplikasi
            $table->unique(['source', 'external_id'], 'innovations_source_external_id_unique');
            
            // Tambahkan index untuk performa query
            $table->index(['employee_number', 'title']);
            $table->index('team');
            $table->index('category');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('innovations', function (Blueprint $table) {
            $table->dropUnique('innovations_source_external_id_unique');
            $table->dropIndex(['employee_number', 'title']);
            $table->dropIndex(['team']);
            $table->dropIndex(['category']);
            $table->dropIndex(['status']);
        });
    }
};
