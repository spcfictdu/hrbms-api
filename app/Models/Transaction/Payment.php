<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = "payments";

    protected $fillable = [
        "payment_type",
        "amount_received"
    ];

    protected $hidden = [
        "id",
        "created_at",
        "updated_at"
    ];

    protected function transaction() {
        return $this->belongsTo(Transaction::class, 'payment_id');
    }
}
