<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add indexes for better performance
            $table->index(['status', 'start_date']);
            $table->index(['city', 'status']);
            $table->index(['organizer_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index('start_date');
            $table->index('status');

            // Add fulltext search index (MySQL only)
            if (DB::connection()->getDriverName() === 'mysql') {
                $table->fullText(['title', 'description', 'venue']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['status', 'start_date']);
            $table->dropIndex(['city', 'status']);
            $table->dropIndex(['organizer_id', 'status']);
            $table->dropIndex(['category_id', 'status']);
            $table->dropIndex(['start_date']);
            $table->dropIndex(['status']);

            // Drop fulltext index if exists
            if (DB::connection()->getDriverName() === 'mysql') {
                $table->dropFullText(['title', 'description', 'venue']);
            }
        });
    }
};
