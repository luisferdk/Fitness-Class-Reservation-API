<?php

namespace Database\Factories;

use App\Enums\RoleCode;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'avatar_url' => fake()->optional(0.3)->imageUrl(200, 200, 'people'),
            'role' => fake()->randomElement(RoleCode::cases()),
            'status' => fake()->randomElement(UserStatus::cases()),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): self
    {
        return $this->state(fn() => ['role' => RoleCode::ADMIN]);
    }

    public function instructor(): self
    {
        return $this->state(fn() => ['role' => RoleCode::INSTRUCTOR]);
    }

    public function student(): self
    {
        return $this->state(fn() => ['role' => RoleCode::STUDENT]);
    }

    public function active(): self
    {
        return $this->state(fn() => ['status' => UserStatus::ACTIVE]);
    }

    public function suspended(): self
    {
        return $this->state(fn() => ['status' => UserStatus::SUSPENDED]);
    }

    public function deleted(): self
    {
        return $this->state(fn() => ['status' => UserStatus::DELETED]);
    }
}
