<?php

namespace App\Models\Guest;

use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guests';

    protected $fillable = [
        'reference_number',
        'first_name',
        'middle_name',
        'last_name',
        'province',
        'city',
        'phone_number',
        'email',
        'id_type',
        'id_number',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'full_name'
    ];

    protected function transaction()
    {
        return $this->hasMany(Transaction::class, 'guest_id');
    }

    public function getFullNameAttribute()
    {
        $fullName = "{$this->last_name}, {$this->first_name}";
        if ($this->middle_name) {
            $fullName .= " {$this->middle_name}";
        }
        // if ($this->suffix) {
        //     $fullName .= " {$this->suffix}";
        // }
        return $fullName;
    }
}
