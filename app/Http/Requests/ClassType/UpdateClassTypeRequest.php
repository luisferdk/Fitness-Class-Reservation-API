<?php


namespace App\Http\Requests\ClassType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassTypeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => ['sometimes', 'string', 'max:255', Rule::unique('class_types', 'name')->ignore($this->class_type?->id ?? $this->route('class_type'))],
      'description' => ['sometimes', 'nullable', 'string'],
      'default_capacity' => ['sometimes', 'integer', 'min:1'],
      'min_attendees' => ['sometimes', 'integer', 'min:1'],
      'is_active' => ['sometimes', 'boolean'],
    ];
  }

  public function passedValidation(): void
  {
    $cap = (int) ($this->default_capacity ?? $this->class_type->default_capacity ?? 0);
    $min = (int) ($this->min_attendees ?? $this->class_type->min_attendees ?? 2);

    if ($min > $cap) {
      abort(response()->json([
        'message' => 'Validation error',
        'errors' => ['min_attendees' => ['min_attendees cannot exceed default_capacity']]
      ], 422));
    }
  }
}
