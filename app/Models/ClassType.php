<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'default_capacity',
        'min_attendees',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'default_capacity' => 'integer',
        'min_attendees' => 'integer',
    ];
}