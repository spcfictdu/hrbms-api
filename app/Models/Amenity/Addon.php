<?php

namespace App\Models\amenity;

use App\Models\Amenity\BookingAddon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price'
    ];

    protected $hidden = [
        'id'
    ];

    public function bookingAddons(){
        return $this->hasMany(BookingAddon::class);
    }

    
}
