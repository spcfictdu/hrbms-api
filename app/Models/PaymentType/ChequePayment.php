<?php

namespace App\Models\PaymentType;

use App\Models\Transaction\Payment;
use App\Models\PaymentType\Bank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChequePayment extends Model
{
    protected $fillable = [ 
        'payment_id', 
        'cheque_number', 
        'bank_name',
        'bank_id'
    ];

    use HasFactory;

    public function payments(){
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function bank() {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
