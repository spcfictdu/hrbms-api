<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'filename'
    ];

    protected $hidden = [
        'id',
        'room_type_id'
    ];

    public $timestamps = false;
}
