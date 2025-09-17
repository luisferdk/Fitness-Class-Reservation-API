<?php

namespace Database\Factories;

use App\Enums\SessionStatus;
use App\Models\ClassSchedule;
use App\Models\ClassType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassSession>
 */
class ClassSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = fake()->dateTimeBetween('now', '+2 months');
        $endAt = fake()->dateTimeBetween($startAt, $startAt->modify('+2 hours'));

        return [
            'class_type_id' => ClassType::factory(),
            'instructor_id' => User::factory()->instructor(),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'capacity' => fake()->numberBetween(5, 30),
            'min_attendees' => fake()->numberBetween(1, 5),
            'status' => fake()->randomElement(SessionStatus::cases()),
            'generated_from_schedule_id' => fake()->optional(0.7)->randomElement(ClassSchedule::pluck('id')->toArray()),
        ];
    }

    /**
     * Create a scheduled session
     */
    public function scheduled(): self
    {
        return $this->state(fn() => ['status' => SessionStatus::SCHEDULED]);
    }

    /**
     * Create a confirmed session
     */
    public function confirmed(): self
    {
        return $this->state(fn() => ['status' => SessionStatus::CONFIRMED]);
    }

    /**
     * Create a completed session
     */
    public function completed(): self
    {
        return $this->state(fn() => ['status' => SessionStatus::COMPLETED]);
    }

    /**
     * Create a canceled session due to low attendance
     */
    public function canceledLowAttendance(): self
    {
        return $this->state(fn() => ['status' => SessionStatus::CANCELED_LOW_ATTENDANCE]);
    }

    /**
     * Create a session in the future
     */
    public function future(): self
    {
        $startAt = fake()->dateTimeBetween('+1 day', '+2 months');
        $endAt = fake()->dateTimeBetween($startAt, $startAt->modify('+2 hours'));

        return $this->state(fn() => [
            'start_at' => $startAt,
            'end_at' => $endAt,
        ]);
    }

    /**
     * Create a session in the past
     */
    public function past(): self
    {
        $startAt = fake()->dateTimeBetween('-2 months', '-1 day');
        $endAt = fake()->dateTimeBetween($startAt, $startAt->modify('+2 hours'));

        return $this->state(fn() => [
            'start_at' => $startAt,
            'end_at' => $endAt,
        ]);
    }
}
