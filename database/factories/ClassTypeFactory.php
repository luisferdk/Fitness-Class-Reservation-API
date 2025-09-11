<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\ClassType> */
class ClassTypeFactory extends Factory
{
  public function definition(): array
  {
    $cap = fake()->numberBetween(5, 30);
    $min = fake()->numberBetween(1, max(1, $cap - 1));

    return [
      'name' => fake()->unique()->words(2, true),
      'description' => fake()->optional()->sentence(),
      'default_capacity' => $cap,
      'min_attendees' => $min,
      'is_active' => fake()->boolean(90),
    ];
  }
}
