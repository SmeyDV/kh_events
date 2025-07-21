<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
  // Public: Get all categories with event count
  public function index(Request $request): JsonResponse
  {
    $categories = Category::withCount('events')
      ->orderBy('name')
      ->get();
    return response()->json($categories);
  }
}
