<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleCode;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;


    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'role',
        'status'
    ];



    protected $visible = [
        'id',
        'name',
        'email',
        'avatar_url'
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => RoleCode::class,
            'status' => UserStatus::class,
        ];
    }

    /**
     * Get the class schedules for the instructor.
     */
    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'instructor_id');
    }

    /**
     * Get the class sessions for the instructor.
     */
    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class, 'instructor_id');
    }

    /**
     * Get the reservations for the user.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
