<?php

namespace App\Models\Transaction;

use App\Models\CashierSession\CashierSession;
use App\Models\Transaction\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoidRefund extends Model
{
    use HasFactory;

    protected $table = "voids_refunds";

    protected $fillable = [
        "type",
        "item",
        "transaction_id",
        "addon_id",
        "cashier_session_id",
        "amount",
    ];

    protected $hidden = [
        "id",
        "created_at",
        "updated_at"
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function bookingAddon()
    {
        return $this->belongsTo(Transaction::class, 'addon_id');
    }

    public function cashierSession()
    {
        return $this->belongsTo(CashierSession::class, 'cashier_session_id');
    }
}
