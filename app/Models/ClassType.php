<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'default_capacity',
        'min_attendees',
        'is_active',
    ];

    protected $visible = [
        'id',
        'name',
        'description',
        'default_capacity',
        'min_attendees',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'default_capacity' => 'integer',
        'min_attendees' => 'integer',
    ];

    /**
     * Get the class schedules for the class type.
     */
    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }

    /**
     * Get the class sessions for the class type.
     */
    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class);
    }
}