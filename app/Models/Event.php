<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'venue',
        'start_date',
        'end_date',
        'capacity',
        'ticket_price',
        'status',
        'image_path',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'ticket_price' => 'decimal:2',
    ];

    /**
     * Get the organizer that owns the event.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the bookings for the event.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the total number of tickets sold.
     */
    public function getTicketsSoldAttribute(): int
    {
        return $this->bookings()->where('status', 'confirmed')->sum('quantity');
    }

    /**
     * Get the remaining tickets available.
     */
    public function getRemainingTicketsAttribute(): ?int
    {
        if ($this->capacity === null) {
            return null; // Unlimited
        }

        return max(0, $this->capacity - $this->tickets_sold);
    }

    /**
     * Check if the event is sold out.
     */
    public function isSoldOut(): bool
    {
        if ($this->capacity === null) {
            return false; // Unlimited capacity
        }

        return $this->remaining_tickets <= 0;
    }

    /**
     * Check if the event is available for booking.
     */
    public function isAvailableForBooking(): bool
    {
        return $this->isPublished() &&
            !$this->isCancelled() &&
            !$this->isSoldOut() &&
            $this->start_date->isFuture();
    }

    /**
     * Scope a query to only include published events.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Check if the event is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if the event is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
