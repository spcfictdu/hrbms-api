<?php

namespace App\Models\Transaction;


use App\Models\Discount\Voucher;
use App\Models\Discount\Discount;
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
        "discount_id",
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

    public function discount(){
        return $this->belongsTo(Discount::class, 'discount_id');
    }
    public function voucher(){
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
}
