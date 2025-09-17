<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class schedule model representing recurring class schedules
 */
class ClassSchedule extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'class_type_id',
    'instructor_id',
    'weekday',
    'start_time',
    'end_time',
    'capacity',
    'min_attendees',
    'is_active',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'weekday' => 'integer',
    'start_time' => 'datetime:H:i',
    'end_time' => 'datetime:H:i',
    'capacity' => 'integer',
    'min_attendees' => 'integer',
    'is_active' => 'boolean',
  ];

  /**
   * Get the class type that owns the schedule.
   */
  public function classType(): BelongsTo
  {
    return $this->belongsTo(ClassType::class);
  }

  /**
   * Get the instructor that owns the schedule.
   */
  public function instructor(): BelongsTo
  {
    return $this->belongsTo(User::class, 'instructor_id');
  }

  /**
   * Get the class sessions generated from this schedule.
   */
  public function classSessions(): HasMany
  {
    return $this->hasMany(ClassSession::class, 'generated_from_schedule_id');
  }
}
