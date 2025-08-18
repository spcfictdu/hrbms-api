<?php

namespace App\Models\Discount;

use App\Models\Transaction\Payment;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherDiscount extends Model
{
    use HasFactory;

    protected $fillable = ['discount','value', 'payment_id', 'transaction_id', 'voucher_id'];

    public function payment(){
        return $this->belongsTo(Payment::class);
    }
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }
    public function voucher(){
        return $this->belongsTo(Voucher::class);
    }
}
