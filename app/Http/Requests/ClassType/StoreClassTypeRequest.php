<?php
namespace App\Http\Requests\ClassType;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassTypeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255', 'unique:class_types,name'],
      'description' => ['nullable', 'string'],
      'default_capacity' => ['nullable', 'integer'],
      'min_attendees' => ['nullable', 'integer', 'min:1'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }

  public function passedValidation(): void
  {
    $cap = (int) ($this->default_capacity ?? 0);
    $min = (int) ($this->min_attendees ?? 2);

    if ($min > $cap) {
      abort(response()->json([
        'message' => 'Validation error',
        'errors' => ['min_attendees' => ['min_attendees cannot exceed default_capacity']]
      ], 422));
    }
  }
}
