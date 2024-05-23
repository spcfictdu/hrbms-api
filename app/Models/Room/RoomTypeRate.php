<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Room\RoomType;

class RoomTypeRate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_number',
        'room_type_id',
        'discount_name',
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
        'room_type_id',
        'deleted_at'
    ];

    public $timestamps = false;

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
