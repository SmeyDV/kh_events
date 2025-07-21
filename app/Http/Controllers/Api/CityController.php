<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
  // Public: Get all cities with event count
  public function index(Request $request): JsonResponse
  {
    $cities = City::withCount('events')->orderBy('name')->get();
    return response()->json($cities);
  }
}
