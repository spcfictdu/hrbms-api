<?php

namespace App\Models\Amenity;

use App\Models\Amenity\Addon;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAddOn extends Model
{
    use HasFactory;

    
    protected $table = 'booking_addons'; // Ensure this matches your actual table name

    protected $fillable = [
        'transaction_id',
        'addon_id',
        'name',
        'quantity',
        'total_price'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function addon(){
        return $this->belongsTo(Addon::class, 'addon_id');
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
                                                                                                               
}
