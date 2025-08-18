<?php

namespace App\Models\Discount;

use App\Models\Transaction\Payment;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeniorPwdDiscount extends Model
{
    protected $fillable = ['payment_id', 'transaction_id', 'discount', 'id_number', 'value'];
    use HasFactory;

    public function payment(){
        return $this->belongsTo(Payment::class);
    }
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }
}
