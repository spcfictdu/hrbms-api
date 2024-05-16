<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailabilityCalendar extends Model
{
    use HasFactory;

    protected $table = 'availability_calendars';

    protected $fillable = [
        'status',
    ];
}
