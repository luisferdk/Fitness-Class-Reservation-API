<?php

namespace Database\Factories;

use App\Models\ClassType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassSchedule>
 */
class ClassScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = fake()->time('H:i');
        $endTime = fake()->time('H:i', $startTime);

        return [
            'class_type_id' => ClassType::factory(),
            'instructor_id' => User::factory()->instructor(),
            'weekday' => fake()->numberBetween(0, 6),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'capacity' => fake()->numberBetween(5, 30),
            'min_attendees' => fake()->numberBetween(1, 5),
            'is_active' => fake()->boolean(85),
        ];
    }

    /**
     * Create a schedule for a specific weekday
     */
    public function weekday(int $weekday): self
    {
        return $this->state(fn() => ['weekday' => $weekday]);
    }

    /**
     * Create an active schedule
     */
    public function active(): self
    {
        return $this->state(fn() => ['is_active' => true]);
    }

    /**
     * Create an inactive schedule
     */
    public function inactive(): self
    {
        return $this->state(fn() => ['is_active' => false]);
    }
}
