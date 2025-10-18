<?php

namespace App\Models\PaymentType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentType\{
    ChequePayment,
    CreditCardPayment
};

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function chequePayment() {
        return $this->hasMany(ChequePayment::class);
    }

    public function creditCardPayment() {
        return $this->hasMany(CreditCardPayment::class);
    }
}
