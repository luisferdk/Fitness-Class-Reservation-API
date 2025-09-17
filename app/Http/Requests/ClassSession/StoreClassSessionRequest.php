<?php

namespace App\Http\Requests\ClassSession;

use App\Enums\SessionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for storing class sessions
 */
class StoreClassSessionRequest extends FormRequest
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
      'start_at' => ['required', 'date', 'after:now'],
      'end_at' => ['required', 'date', 'after:start_at'],
      'capacity' => ['required', 'integer', 'min:1'],
      'min_attendees' => ['required', 'integer', 'min:1'],
      'status' => ['sometimes', Rule::enum(SessionStatus::class)],
      'generated_from_schedule_id' => ['nullable', 'exists:class_schedules,id'],
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
      'start_at.required' => 'The start time is required.',
      'start_at.date' => 'The start time must be a valid date.',
      'start_at.after' => 'The start time must be in the future.',
      'end_at.required' => 'The end time is required.',
      'end_at.date' => 'The end time must be a valid date.',
      'end_at.after' => 'The end time must be after the start time.',
      'capacity.required' => 'The capacity is required.',
      'capacity.integer' => 'The capacity must be a number.',
      'capacity.min' => 'The capacity must be at least 1.',
      'min_attendees.required' => 'The minimum attendees is required.',
      'min_attendees.integer' => 'The minimum attendees must be a number.',
      'min_attendees.min' => 'The minimum attendees must be at least 1.',
      'status.enum' => 'The status must be a valid session status.',
      'generated_from_schedule_id.exists' => 'The selected schedule does not exist.',
    ];
  }
}
