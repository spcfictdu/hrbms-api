<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'room_number',
        'room_type_id',
    ];

    protected $hidden = [
        'id',
        'room_type_id'
    ];

    public $timestamps = false;
}
