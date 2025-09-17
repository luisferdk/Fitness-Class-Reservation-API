<?php

namespace App\Http\Requests\Reservation;

use App\Enums\ReservationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for storing reservations
 */
class StoreReservationRequest extends FormRequest
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
      'session_id' => ['required', 'exists:class_sessions,id'],
      'user_id' => ['required', 'exists:users,id'],
      'status' => ['sometimes', Rule::enum(ReservationStatus::class)],
      'cancellation_deadline' => ['required', 'date', 'after:now'],
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
      'session_id.required' => 'The session is required.',
      'session_id.exists' => 'The selected session does not exist.',
      'user_id.required' => 'The user is required.',
      'user_id.exists' => 'The selected user does not exist.',
      'status.enum' => 'The status must be a valid reservation status.',
      'cancellation_deadline.required' => 'The cancellation deadline is required.',
      'cancellation_deadline.date' => 'The cancellation deadline must be a valid date.',
      'cancellation_deadline.after' => 'The cancellation deadline must be in the future.',
    ];
  }
}
