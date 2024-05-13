<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Room\RoomType;

class Booking extends Model
{
    use HasFactory;

    protected $table = "bookings";

    protected $fillable = [
        'room_id',
        'status',
        'payment_type',
        'check_in_date',
        'check_in_time',
        'check_out_date',
        'check_out_time',
        'number_of_guest',
        'guest_id'
    ];

    protected $hidden = [
        'id',
        'room_id',
        'guest_id',
        'created_at',
        'updated_at'
    ];

    protected function room() {
        return $this->belongsTo(RoomType::class, 'room_id');
    }
}
