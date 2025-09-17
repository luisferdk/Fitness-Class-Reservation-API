<?php

namespace App\Http\Requests\ClassSession;

use App\Enums\SessionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for updating class sessions
 */
class UpdateClassSessionRequest extends FormRequest
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
      'class_type_id' => ['sometimes', 'exists:class_types,id'],
      'instructor_id' => ['sometimes', 'exists:users,id'],
      'start_at' => ['sometimes', 'date'],
      'end_at' => ['sometimes', 'date', 'after:start_at'],
      'capacity' => ['sometimes', 'integer', 'min:1'],
      'min_attendees' => ['sometimes', 'integer', 'min:1'],
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
      'class_type_id.exists' => 'The selected class type does not exist.',
      'instructor_id.exists' => 'The selected instructor does not exist.',
      'start_at.date' => 'The start time must be a valid date.',
      'end_at.date' => 'The end time must be a valid date.',
      'end_at.after' => 'The end time must be after the start time.',
      'capacity.integer' => 'The capacity must be a number.',
      'capacity.min' => 'The capacity must be at least 1.',
      'min_attendees.integer' => 'The minimum attendees must be a number.',
      'min_attendees.min' => 'The minimum attendees must be at least 1.',
      'status.enum' => 'The status must be a valid session status.',
      'generated_from_schedule_id.exists' => 'The selected schedule does not exist.',
    ];
  }
}
