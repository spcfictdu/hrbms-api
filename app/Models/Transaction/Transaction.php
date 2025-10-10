<?php

namespace App\Models\Transaction;

use App\Models\Amenity\BookingAddon;
use App\Models\Guest\Guest;
use App\Models\Room\Room;
use App\Models\User;
use App\Models\Transaction\Flight;
use App\Models\Transaction\VoidRefund;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Folio;
use App\Models\Discount\SeniorPwdDiscount;
use App\Models\Discount\VoucherDiscount;
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
        'payment_status',
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
        return $this->hasMany(Payment::class, 'transaction_id');
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

    public function voidRefund()
    {
        return $this->hasMany(VoidRefund::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function voucherDiscount()
    {
        return $this->hasOne(VoucherDiscount::class);
    }

    public function seniorPwdDiscount()
    {
        return $this->hasOne(SeniorPwdDiscount::class);
    }

    public function flight()
    {
        return $this->hasMany(Flight::class);
    }

    public function folio()
    {
        return $this->hasOne(Folio::class);
    }
}
