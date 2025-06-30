<?php

namespace App\Models\Discount;

use App\Models\Transaction\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    protected $fillable = [
        'reference_number',
        'code',
        'value', 
        'usage',
        'status',
        'expires_at'
    ];

    protected $hidden = [   
        'created_at',
        'updated_at',
        'id'
    ];
    
    use HasFactory;

    public function payments(){
        return $this->hasmany(Payment::class)->withDefault();
    }
    public function voucherDiscounts(){
        return $this->hasMany(VoucherDiscount::class);
    }
}
