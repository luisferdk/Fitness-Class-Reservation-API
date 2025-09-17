<?php

namespace App\Http\Requests\ClassSchedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for storing class schedules
 */
class StoreClassScheduleRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'class_type_id' => ['required', 'exists:class_types,id'],
      'instructor_id' => ['required', 'exists:users,id'],
      'weekday' => ['required', 'integer', 'min:0', 'max:6'],
      'start_time' => ['required', 'date_format:H:i'],
      'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
      'capacity' => ['nullable', 'integer', 'min:1'],
      'min_attendees' => ['nullable', 'integer', 'min:1'],
      'is_active' => ['boolean'],
    ];
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'class_type_id.required' => 'The class type is required.',
      'class_type_id.exists' => 'The selected class type does not exist.',
      'instructor_id.required' => 'The instructor is required.',
      'instructor_id.exists' => 'The selected instructor does not exist.',
      'weekday.required' => 'The weekday is required.',
      'weekday.integer' => 'The weekday must be a number.',
      'weekday.min' => 'The weekday must be at least 0 (Sunday).',
      'weekday.max' => 'The weekday must be at most 6 (Saturday).',
      'start_time.required' => 'The start time is required.',
      'start_time.date_format' => 'The start time must be in HH:MM format.',
      'end_time.required' => 'The end time is required.',
      'end_time.date_format' => 'The end time must be in HH:MM format.',
      'end_time.after' => 'The end time must be after the start time.',
      'capacity.integer' => 'The capacity must be a number.',
      'capacity.min' => 'The capacity must be at least 1.',
      'min_attendees.integer' => 'The minimum attendees must be a number.',
      'min_attendees.min' => 'The minimum attendees must be at least 1.',
      'is_active.boolean' => 'The active status must be true or false.',
    ];
  }
}
