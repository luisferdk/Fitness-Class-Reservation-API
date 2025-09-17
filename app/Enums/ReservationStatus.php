<?php

namespace App\Enums;

/**
 * Enum for reservation status values
 */
enum ReservationStatus: string
{
  case BOOKED = 'booked';
  case CANCELED_BY_USER = 'canceled_by_user';
  case CANCELED_BY_SYSTEM = 'canceled_by_system';
  case ATTENDED = 'attended';
  case NO_SHOW = 'no_show';
}
