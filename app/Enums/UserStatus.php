<?php

namespace App\Enums;

/**
 * Enum for user status values
 */
enum UserStatus: string
{
  case ACTIVE = 'active';
  case SUSPENDED = 'suspended';
  case DELETED = 'deleted';
}
