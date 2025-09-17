<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Reservation model representing user reservations for class sessions
 */
class Reservation extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'session_id',
    'user_id',
    'status',
    'booked_at',
    'canceled_at',
    'checked_in_at',
    'cancellation_deadline',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'status' => ReservationStatus::class,
    'booked_at' => 'datetime',
    'canceled_at' => 'datetime',
    'checked_in_at' => 'datetime',
    'cancellation_deadline' => 'datetime',
  ];

  /**
   * Get the class session that owns the reservation.
   */
  public function classSession(): BelongsTo
  {
    return $this->belongsTo(ClassSession::class, 'session_id');
  }

  /**
   * Get the user that owns the reservation.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
