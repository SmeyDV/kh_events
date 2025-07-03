<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
  /**
   * Display a listing of all categories.
   */
  public function index(): View
  {
    $categories = Category::withCount('events')->orderBy('name')->get();

    return view('categories.index', compact('categories'));
  }

  /**
   * Display events for a specific category.
   */
  public function show(Category $category, Request $request): View
  {
    $filters = [];

    if ($request->filled('search')) {
      $filters['search'] = $request->input('search');
    }

    if ($request->filled('city_id')) {
      $filters['city_id'] = $request->input('city_id');
    }

    // Get events for this category
    $query = Event::published()
      ->where('category_id', $category->id)
      ->with(['organizer', 'category', 'city']);

    if (isset($filters['search'])) {
      $query->search($filters['search']);
    }

    if (isset($filters['city_id'])) {
      $query->inCity($filters['city_id']);
    }

    $events = $query->orderBy('start_date', 'asc')->paginate(12);
    $cities = City::orderBy('name')->get();

    // Get category statistics
    $totalEvents = Event::where('category_id', $category->id)->count();
    $upcomingEvents = Event::where('category_id', $category->id)
      ->where('start_date', '>=', now())
      ->count();

    return view('categories.show', compact('category', 'events', 'cities', 'totalEvents', 'upcomingEvents'));
  }
}
