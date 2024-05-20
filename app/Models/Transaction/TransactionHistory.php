<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    protected $table = "transaction_histories";

    protected $fillable = [
        'check_in_date',
        'check_in_time',
        'check_out_date',
        'check_out_time',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected function transaction()
    {
        return $this->hasMany(Transaction::class, 'payment_id');
    }
}
