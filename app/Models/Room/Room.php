<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Room\RoomType;
use App\Models\Transaction\Transaction;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'room_number',
        'room_floor',
        'room_type_id',
        'status'
    ];

    protected $hidden = [
        'id',
        'room_type_id'
    ];

    public $timestamps = false;

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    protected function transactions()
    {
        return $this->hasMany(Transaction::class, 'room_id');
    }
}
