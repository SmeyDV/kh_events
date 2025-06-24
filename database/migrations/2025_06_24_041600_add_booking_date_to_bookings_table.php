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
        Schema::table('bookings', function (Blueprint $table) {
            $table->datetime('booking_date')->nullable()->after('notes');
        });

        // Update existing records to have a booking_date
        DB::statement('UPDATE bookings SET booking_date = created_at WHERE booking_date IS NULL');

        // Make the column not nullable after updating existing records
        Schema::table('bookings', function (Blueprint $table) {
            $table->datetime('booking_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('booking_date');
        });
    }
};
