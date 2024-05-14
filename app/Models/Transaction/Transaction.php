<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Room\RoomType;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "transactions";

    protected $fillable = [
        'room_id',
        'status',
        'payment_id',
        'payment_type',
        'check_in_date',
        'check_in_time',
        'check_out_date',
        'check_out_time',
        'number_of_guest',
        'guest_id'
    ];

    protected $hidden = [
        'id',
        'room_id',
        'payment_id',
        'guest_id',
        'created_at',
        'updated_at'
    ];

    protected function room() {
        return $this->belongsTo(RoomType::class, 'room_id');
    }

    protected function payment() {
        return $this->hasMany(Payment::class, 'payment_id');
    }
}
