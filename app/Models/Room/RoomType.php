<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\{
    Room\RoomTypeAmenity,
    Room\RoomTypeImage,
    Room\RoomTypeRate
};

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'name',
        'description',
        'bed_size',
        'property_size',
        'is_non_smoking',
        'balcony_or_terrace',
        'capacity'
    ];

    protected $hidden = [
        'id'
    ];

    protected $casts = [
        'is_non_smoking' => 'boolean',
        'balcony_or_terrace' => 'boolean',
    ];

    public $timestamps = false;

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }

    public function amenities()
    {
        return $this->hasMany(RoomTypeAmenity::class, 'room_type_id');
    }

    public function images()
    {
        return $this->hasMany(RoomTypeImage::class, 'room_type_id');
    }

    public function rates()
    {
        return $this->hasMany(RoomTypeRate::class, 'room_type_id');
    }
}
