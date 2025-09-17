<?php

namespace App\Http\Requests\Reservation;

use App\Enums\ReservationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for updating reservations
 */
class UpdateReservationRequest extends FormRequest
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
      'session_id' => ['sometimes', 'exists:class_sessions,id'],
      'user_id' => ['sometimes', 'exists:users,id'],
      'status' => ['sometimes', Rule::enum(ReservationStatus::class)],
      'canceled_at' => ['nullable', 'date'],
      'checked_in_at' => ['nullable', 'date'],
      'cancellation_deadline' => ['sometimes', 'date'],
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
      'session_id.exists' => 'The selected session does not exist.',
      'user_id.exists' => 'The selected user does not exist.',
      'status.enum' => 'The status must be a valid reservation status.',
      'canceled_at.date' => 'The canceled at must be a valid date.',
      'checked_in_at.date' => 'The checked in at must be a valid date.',
      'cancellation_deadline.date' => 'The cancellation deadline must be a valid date.',
    ];
  }
}
