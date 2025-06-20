<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Booking;
use App\Policies\EventPolicy;
use App\Policies\BookingPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array<class-string, class-string|null>
   */
  protected $policies = [
    Event::class => EventPolicy::class,
    Booking::class => BookingPolicy::class,
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }
}
