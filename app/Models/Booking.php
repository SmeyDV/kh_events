<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'event_id',
    'quantity',
    'total_amount',
    'status',
    'payment_status',
    'payment_method',
    'transaction_id',
    'notes',
    'booking_date',
  ];

  protected $casts = [
    'total_amount' => 'decimal:2',
  ];

  /**
   * Get the user that owns the booking.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the event that owns the booking.
   */
  public function event(): BelongsTo
  {
    return $this->belongsTo(Event::class);
  }

  /**
   * Check if the booking is confirmed.
   */
  public function isConfirmed(): bool
  {
    return $this->status === 'confirmed';
  }

  /**
   * Check if the booking is paid.
   */
  public function isPaid(): bool
  {
    return $this->payment_status === 'paid';
  }

  /**
   * Check if the booking is pending.
   */
  public function isPending(): bool
  {
    return $this->status === 'pending';
  }

  /**
   * Check if the booking is cancelled.
   */
  public function isCancelled(): bool
  {
    return $this->status === 'cancelled';
  }

  /**
   * Get the booking status badge color.
   */
  public function getStatusBadgeColorAttribute(): string
  {
    return match ($this->status) {
      'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
      'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
      'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
      'refunded' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
      default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
    };
  }

  /**
   * Get the payment status badge color.
   */
  public function getPaymentStatusBadgeColorAttribute(): string
  {
    return match ($this->payment_status) {
      'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
      'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
      'failed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
      'refunded' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
      default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
    };
  }
}
