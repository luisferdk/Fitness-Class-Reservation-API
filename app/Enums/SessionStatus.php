<?php

namespace App\Enums;

/**
 * Enum for session status values
 */
enum SessionStatus: string
{
  case SCHEDULED = 'scheduled';
  case CONFIRMED = 'confirmed';
  case CANCELED_LOW_ATTENDANCE = 'canceled_low_attendance';
  case COMPLETED = 'completed';
}
