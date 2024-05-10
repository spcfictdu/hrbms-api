<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'type',
        'start_date',
        'end_date',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday'
    ];

    protected $hidden = [
        'id',
        'room_type_id'
    ];

    public $timestamps = false;
}
