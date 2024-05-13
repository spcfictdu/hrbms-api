<?php

namespace App\Models\Guest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $table = 'guests';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'province',
        'city',
        'phone_number',
        'email',
        'id_type',
        'id_number',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];
}
