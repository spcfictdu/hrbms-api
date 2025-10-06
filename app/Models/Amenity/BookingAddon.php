<?php

namespace App\Models\Amenity;

use App\Models\Amenity\Addon;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\VoidRefund;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Payment;

class BookingAddOn extends Model
{
    use HasFactory;

    
    protected $table = 'booking_addons'; // Ensure this matches your actual table name

    protected $fillable = [
        'transaction_id',
        'payment_id',        
        'purchase_batch',
        'addon_id',
        'name',
        'quantity',
        'total_price',
        'payment_status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function addon(){
        return $this->belongsTo(Addon::class, 'addon_id');
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function voidRefund() {
        return $this->hasOne(VoidRefund::class);
    }
    
    public function payment(){
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
