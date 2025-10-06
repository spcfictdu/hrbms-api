<?php

namespace App\Models\Transaction;

use App\Models\User;
use App\Models\Discount\Voucher;
use App\Models\PaymentType\Cash;
use App\Models\Discount\Discount;
use App\Models\Amenity\BookingAddOn;
use Illuminate\Database\Eloquent\Model;
use App\Models\Discount\PaymentDiscount;
use App\Models\Discount\VoucherDiscount;
use App\Models\PaymentType\GcashPayment;
use App\Models\PaymentType\ChequePayment;
use App\Models\Discount\SeniorPwdDiscount;
use App\Models\CashierSession\CashierSession;
use App\Models\PaymentType\CreditCardPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = "payments";

    protected $fillable = [
        "transaction_id",
        "user_id",
        "cashier_session_id",
        "payment_type",
        "amount_received",
        "payment_discount_id",
        "discount_id",
        "voucher_id",
    ];

    protected $hidden = [
        "id",
        "transaction_id",
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        "amount_received" => "decimal:2"
    ];

    // protected function transaction()
    // {
    //     return $this->hasMany(Transaction::class, 'payment_id');
    // }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function voucherDiscount()
    {
        return $this->hasOne(VoucherDiscount::class);
    }

    public function seniorPwdDiscount()
    {
        return $this->hasOne(SeniorPwdDiscount::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function chequePayment()
    {
        return $this->hasOne(ChequePayment::class);
    }

    public function creditCartPayment()
    {
        return $this->hasOne(CreditCardPayment::class);
    }

    public function cashierSession()
    {
        return $this->belongsTo(CashierSession::class);
    }

    public function bookingAddon()
    {
        return $this->hasMany(BookingAddOn::class);
    }
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
