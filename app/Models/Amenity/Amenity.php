<?php

namespace App\Models\Amenity;

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
}
