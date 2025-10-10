<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Transaction;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'transaction_id',
        'first_name',
        'last_name',
        'full_name',
        'flight_group',
        'flight_number',
        'departure_date',
        'departure_time',
        'arrival_date',
        'arrival_time',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
