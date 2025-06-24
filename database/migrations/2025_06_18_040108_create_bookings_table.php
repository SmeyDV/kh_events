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
    Schema::create('bookings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
      $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
      $table->integer('quantity');
      $table->decimal('total_amount', 10, 2);
      $table->enum('status', ['pending', 'confirmed', 'cancelled', 'refunded'])->default('pending'); // pending, confirmed, cancelled, refunded
      $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending'); // pending, paid, failed, refunded
      $table->string('payment_method')->nullable(); // stripe, paypal, etc.
      $table->string('transaction_id')->nullable();
      $table->text('notes')->nullable();
      $table->datetime('booking_date');
      $table->timestamps();

      // Prevent duplicate bookings for same user and event
      $table->unique(['user_id', 'event_id']);

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
    Schema::dropIfExists('bookings');
  }
};
