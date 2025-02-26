<?php

namespace App\Models\PaymentType;

use App\Models\Transaction\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditCardPayment extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'payment_id', 
        'card_number', 
        'card_holder_name', 
        'expiration_date',
        'cvc', 
    ];

    public function payments(){
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
