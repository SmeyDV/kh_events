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
        Schema::table('bookings', function (Blueprint $table) {
            // Add indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['event_id', 'status']);
            $table->index(['status', 'booking_date']);
            $table->index('booking_date');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['event_id', 'status']);
            $table->dropIndex(['status', 'booking_date']);
            $table->dropIndex(['booking_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
        });
    }
};
