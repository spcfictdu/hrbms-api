<?php

namespace App\Models\Discount;

use App\Models\Transaction\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    public function payments(){
        return $this->hasmany(Payment::class)->withDefault();
    }
}
