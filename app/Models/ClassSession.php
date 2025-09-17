<?php

namespace App\Models;

use App\Enums\SessionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class session model representing individual class sessions
 */
class ClassSession extends Model
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
    'start_at',
    'end_at',
    'capacity',
    'min_attendees',
    'status',
    'generated_from_schedule_id',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'start_at' => 'datetime',
    'end_at' => 'datetime',
    'capacity' => 'integer',
    'min_attendees' => 'integer',
    'status' => SessionStatus::class,
    'generated_from_schedule_id' => 'integer',
  ];

  /**
   * Get the class type that owns the session.
   */
  public function classType(): BelongsTo
  {
    return $this->belongsTo(ClassType::class);
  }

  /**
   * Get the instructor that owns the session.
   */
  public function instructor(): BelongsTo
  {
    return $this->belongsTo(User::class, 'instructor_id');
  }

  /**
   * Get the schedule that generated this session.
   */
  public function generatedFromSchedule(): BelongsTo
  {
    return $this->belongsTo(ClassSchedule::class, 'generated_from_schedule_id');
  }

  /**
   * Get the reservations for the session.
   */
  public function reservations(): HasMany
  {
    return $this->hasMany(Reservation::class, 'session_id');
  }
}
