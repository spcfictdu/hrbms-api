<?php

namespace App\Models\Transaction;


use App\Models\Discount\Voucher;
use App\Models\PaymentType\Cash;
use App\Models\Discount\Discount;
use App\Models\Discount\PaymentDiscount;
use App\Models\Discount\SeniorPwdDiscount;
use App\Models\Discount\VoucherDiscount;
use App\Models\PaymentType\ChequePayment;
use App\Models\PaymentType\CreditCardPayment;
use App\Models\PaymentType\GcashPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = "payments";

    protected $fillable = [
        "transaction_id",
        "payment_type",
        "amount_received",
        // "payment_discount_id"
        // "discount_id",
        // "voucher_id",
    ];

    protected $hidden = [
        "id",
        "transaction_id",
        "created_at",
        "updated_at"
    ];

    // protected function transaction()
    // {
    //     return $this->hasMany(Transaction::class, 'payment_id');
    // }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function voucherDiscount(){
        return $this->hasOne(VoucherDiscount::class);
    }

    public function seniorPwdDiscount(){
        return $this->hasOne(SeniorPwdDiscount::class);
    }

    public function discount(){
        return $this->belongsTo(Discount::class);
    }

    public function voucher(){
        return $this->belongsTo(Voucher::class);
    }

    public function chequePayment(){
        return $this->hasOne(ChequePayment::class);
    }

    public function creditCartPayment(){
        return $this->hasOne(CreditCardPayment::class);
    }


    
}
