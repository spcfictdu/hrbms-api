<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Amenity\Amenity;

class RoomTypeAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'amenity_id'
    ];

    protected $hidden = [
        'id',
        'room_type_id',
        'amenity_id'
    ];

    public $timestamps = false;

    protected function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id');
    }
}
