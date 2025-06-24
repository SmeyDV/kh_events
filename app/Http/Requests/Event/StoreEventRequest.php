<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEventRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return Auth::check() && Auth::user()->isOrganizer();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'title' => 'required|string|max:255',
      'description' => 'required|string|min:10',
      'start_date' => 'required|date|after:now',
      'end_date' => 'required|date|after:start_date',
      'venue' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'capacity' => 'required|integer|min:1',
      'ticket_price' => 'nullable|numeric|min:0|max:999999.99',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'category_id' => 'required|exists:categories,id',
    ];
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'title.required' => 'Event title is required.',
      'title.max' => 'Event title cannot exceed 255 characters.',
      'description.required' => 'Event description is required.',
      'description.min' => 'Event description must be at least 10 characters.',
      'start_date.required' => 'Start date is required.',
      'start_date.after' => 'Start date must be in the future.',
      'end_date.required' => 'End date is required.',
      'end_date.after' => 'End date must be after start date.',
      'venue.required' => 'Venue is required.',
      'city.required' => 'City is required.',
      'capacity.required' => 'Capacity is required.',
      'capacity.min' => 'Capacity must be at least 1.',
      'ticket_price.numeric' => 'Ticket price must be a valid number.',
      'ticket_price.min' => 'Ticket price cannot be negative.',
      'ticket_price.max' => 'Ticket price cannot exceed 999,999.99.',
      'image.image' => 'The file must be an image.',
      'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
      'image.max' => 'The image may not be greater than 2MB.',
      'category_id.required' => 'Category is required.',
      'category_id.exists' => 'Selected category is invalid.',
    ];
  }
}
