<?php

namespace App\Models\Discount;

use App\Models\Transaction\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;

    protected $hidden = [   
        'created_at',
        'updated_at',
        'id'
    ];

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
