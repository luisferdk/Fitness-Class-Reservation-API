<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\ClassSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookedAt = fake()->dateTimeBetween('-1 month', 'now');
        $cancellationDeadline = fake()->dateTimeBetween($bookedAt, '+1 month');
        $status = fake()->randomElement(ReservationStatus::cases());

        return [
            'session_id' => ClassSession::factory(),
            'user_id' => User::factory()->student(),
            'status' => $status,
            'booked_at' => $bookedAt,
            'canceled_at' => $status->value === 'canceled_by_user' || $status->value === 'canceled_by_system'
                ? fake()->dateTimeBetween($bookedAt, 'now')
                : null,
            'checked_in_at' => $status->value === 'attended'
                ? fake()->dateTimeBetween($bookedAt, 'now')
                : null,
            'cancellation_deadline' => $cancellationDeadline,
        ];
    }

    /**
     * Create a booked reservation
     */
    public function booked(): self
    {
        return $this->state(fn() => ['status' => ReservationStatus::BOOKED]);
    }

    /**
     * Create a canceled reservation by user
     */
    public function canceledByUser(): self
    {
        $bookedAt = fake()->dateTimeBetween('-1 month', 'now');

        return $this->state(fn() => [
            'status' => ReservationStatus::CANCELED_BY_USER,
            'booked_at' => $bookedAt,
            'canceled_at' => fake()->dateTimeBetween($bookedAt, 'now'),
        ]);
    }

    /**
     * Create a canceled reservation by system
     */
    public function canceledBySystem(): self
    {
        $bookedAt = fake()->dateTimeBetween('-1 month', 'now');

        return $this->state(fn() => [
            'status' => ReservationStatus::CANCELED_BY_SYSTEM,
            'booked_at' => $bookedAt,
            'canceled_at' => fake()->dateTimeBetween($bookedAt, 'now'),
        ]);
    }

    /**
     * Create an attended reservation
     */
    public function attended(): self
    {
        $bookedAt = fake()->dateTimeBetween('-1 month', 'now');

        return $this->state(fn() => [
            'status' => ReservationStatus::ATTENDED,
            'booked_at' => $bookedAt,
            'checked_in_at' => fake()->dateTimeBetween($bookedAt, 'now'),
        ]);
    }

    /**
     * Create a no-show reservation
     */
    public function noShow(): self
    {
        return $this->state(fn() => ['status' => ReservationStatus::NO_SHOW]);
    }
}
