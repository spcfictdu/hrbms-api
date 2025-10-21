<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Amenity\BookingAddon,
    Transaction\Transaction
};

class Folio extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'type',
        'transaction_id',
        'booking_addon_id',
        'folio_a_name',
        'folio_a_charge',
        'folio_a_amount',
        'folio_b_name',
        'folio_b_charge',
        'folio_b_amount',
        'folio_c_name',
        'folio_c_charge',
        'folio_c_amount',
        'folio_d_name',
        'folio_d_charge',
        'folio_d_amount',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function bookingAddon() {
        return $this->belongsTo(BookingAddon::class, 'booking_addon_id');
    }
}
