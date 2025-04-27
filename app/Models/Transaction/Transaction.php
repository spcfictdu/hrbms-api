<?php

namespace App\Models\Transaction;

use App\Models\Amenity\BookingAddon;
use App\Models\Guest\Guest;
use App\Models\Room\Room;
use App\Models\User;

use App\Models\Transaction\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "transactions";

    protected $fillable = [
        'reference_number',
        'room_id',
        'room_total',
        'status',
        'payment_type',
        'check_in_date',
        'check_in_time',
        'check_out_date',
        'check_out_time',
        'number_of_guest',
        'guest_id',
        'transaction_history_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'room_id',
        'guest_id',
        // 'transaction_history_id',
        'created_at',
        'updated_at'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // protected function payment()
    // {
    //     return $this->belongsTo(Payment::class, 'payment_id');
    // }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'transaction_id');
    }

    public function transactionHistory()
    {
        return $this->belongsTo(TransactionHistory::class, 'transaction_history_id');
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function getFullCheckInAttribute()
    {
        // 2024-05-24T06:34:09.000000Z
        return $this->check_in_date . 'T' . $this->check_in_time;
    }

    public function getFullCheckOutAttribute()
    {
        return $this->check_out_date . 'T' . $this->check_out_time;
    }

    public function bookingAddOn()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
