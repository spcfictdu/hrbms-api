<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Transaction;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'guest_name',
        'flight_number',
        'departure_date',
        'departure_time',
        'arrival_date',
        'arrival_time',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
