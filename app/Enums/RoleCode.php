<?php

namespace App\Enums;

/**
 * Enum for role code values
 */
enum RoleCode: string
{
  case ADMIN = 'admin';
  case INSTRUCTOR = 'instructor';
  case STUDENT = 'student';
}
