<?php

namespace App\Models\Discount;

use App\Models\Transaction\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherDiscount extends Model
{
    use HasFactory;

    protected $fillable = ['discount','value', 'payment_id'];

    public function payments(){
        return $this->belongsTo(Payment::class);
    }
  
}
