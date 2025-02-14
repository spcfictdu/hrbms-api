<?php

namespace App\Models\Amenity;

use App\Models\Room\RoomType;
use App\Models\Room\RoomTypeAmenity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'name'
    ];

    protected $hidden = [
        'id'
    ];

    public $timestamps = false;

    public function roomTypes()
    {
        return $this->belongsToMany(RoomTypeAmenity::class, 'room_type_amenities',);
    }
}
