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
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('event_id')->constrained()->onDelete('cascade');
      $table->integer('quantity');
      $table->decimal('total_amount', 10, 2);
      $table->string('status')->default('pending'); // pending, confirmed, cancelled, refunded
      $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
      $table->string('payment_method')->nullable(); // stripe, paypal, etc.
      $table->string('transaction_id')->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();

      // Prevent duplicate bookings for same user and event
      $table->unique(['user_id', 'event_id']);
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
