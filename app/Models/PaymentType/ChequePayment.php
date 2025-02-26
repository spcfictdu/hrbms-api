<?php

namespace App\Models\PaymentType;

use App\Models\Transaction\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChequePayment extends Model
{
    protected $fillable = [ 
        'payment_id', 
        'cheque_number', 
        'bank_name',
    ];

    use HasFactory;

    public function payments(){
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
