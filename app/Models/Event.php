<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'venue',
        'city',
        'start_date',
        'end_date',
        'capacity',
        'ticket_price',
        'status',
        'organizer_id',
        'category_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'ticket_price' => 'decimal:2',
    ];

    protected $with = ['organizer', 'category']; // Eager load by default

    /**
     * Boot the model and add event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when event is updated
        static::saved(function ($event) {
            Cache::forget('events.upcoming');
            Cache::forget('events.published');
            Cache::forget("events.city.{$event->city}");

            // Clear all cached published events with different filters
            Cache::forget('events.cities');

            // Clear specific cache keys
            Cache::forget("events.upcoming.3");
            Cache::forget("events.upcoming.5");
            Cache::forget("events.upcoming.10");

            // Clear tickets sold cache for this event
            Cache::forget("event.{$event->id}.tickets_sold");
        });

        static::deleted(function ($event) {
            Cache::forget('events.upcoming');
            Cache::forget('events.published');
            Cache::forget("events.city.{$event->city}");

            // Clear all cached published events with different filters
            Cache::forget('events.cities');

            // Clear specific cache keys
            Cache::forget("events.upcoming.3");
            Cache::forget("events.upcoming.5");
            Cache::forget("events.upcoming.10");

            // Clear tickets sold cache for this event
            Cache::forget("event.{$event->id}.tickets_sold");
        });
    }

    /**
     * Get the organizer that owns the event.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get the bookings for the event.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the images for the event.
     */
    public function images(): HasMany
    {
        return $this->hasMany(EventImage::class);
    }

    /**
     * Get the total number of tickets sold with caching.
     */
    public function getTicketsSoldAttribute(): int
    {
        return Cache::remember("event.{$this->id}.tickets_sold", 300, function () {
            return $this->bookings()->where('status', 'confirmed')->sum('quantity');
        });
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
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>', now());
    }

    /**
     * Scope a query to only include events in a specific city.
     */
    public function scopeInCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    /**
     * Scope a query to search events by title or description.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to only include events with available capacity.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('capacity')
                ->orWhereRaw('capacity > (SELECT COALESCE(SUM(quantity), 0) FROM bookings WHERE bookings.event_id = events.id AND bookings.status = "confirmed")');
        });
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

    /**
     * Get the category that owns the event.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get cached upcoming events.
     */
    public static function getUpcomingEvents(int $limit = 3)
    {
        return Cache::remember("events.upcoming.{$limit}", 300, function () use ($limit) {
            return static::published()
                ->upcoming()
                ->with(['organizer', 'category'])
                ->orderBy('start_date', 'asc')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get cached published events with pagination.
     */
    public static function getPublishedEvents($perPage = 10, $filters = [])
    {
        $cacheKey = "events.published." . md5(serialize($filters));

        return Cache::remember($cacheKey, 300, function () use ($perPage, $filters) {
            $query = static::published()->with(['organizer', 'category']);

            if (isset($filters['search'])) {
                $query->search($filters['search']);
            }

            if (isset($filters['city'])) {
                $query->inCity($filters['city']);
            }

            return $query->orderBy('start_date', 'asc')->paginate($perPage);
        });
    }

    /**
     * Get available cities for filtering.
     */
    public static function getAvailableCities()
    {
        return Cache::remember('events.cities', 3600, function () {
            return static::published()
                ->whereNotNull('city')
                ->distinct()
                ->pluck('city')
                ->sort()
                ->values();
        });
    }

    /**
     * Clear all event-related cache.
     */
    public static function clearAllCache()
    {
        Cache::forget('events.upcoming');
        Cache::forget('events.published');
        Cache::forget('events.cities');

        // Clear specific cache keys
        Cache::forget("events.upcoming.3");
        Cache::forget("events.upcoming.5");
        Cache::forget("events.upcoming.10");

        // Clear all tickets sold cache
        $events = static::all();
        foreach ($events as $event) {
            Cache::forget("event.{$event->id}.tickets_sold");
        }
    }
}
