<?php

namespace App\Models\Transaction;

use App\Models\Amenity\BookingAddon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    use HasFactory;

    protected $table = 'payment_allocations';

    protected $fillable = [
        'payment_id',
        'transaction_id',
        'booking_addon_id',
        'item',
        'type',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function bookingAddon()
    {
        return $this->belongsTo(BookingAddon::class, 'booking_addon_id');
    }
}
