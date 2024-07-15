<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class RoomAvailable implements ValidationRule
{
    protected $checkInDate;
    protected $checkOutDate;

    public function __construct($checkInDate, $checkOutDate)
    {
        $this->checkInDate = $checkInDate;
        $this->checkOutDate = $checkOutDate;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $value is the room's reference number or ID depending on how you're validating
        // If it's the reference number, you should get the room ID first
        $roomId = DB::table('rooms')->where('reference_number', $value)->value('id');

        // Check for any transactions that overlap with the given dates for this room
        $overlap = DB::table('transactions')
            ->where('room_id', $roomId)
            // ->where('status', 'RESERVED')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereBetween('check_in_date', [$this->checkInDate, $this->checkOutDate])
                        ->orWhereBetween('check_out_date', [$this->checkInDate, $this->checkOutDate]);
                })
                    ->orWhere(function ($q) {
                        $q->where('check_in_date', '<=', $this->checkInDate)
                            ->where('check_out_date', '>=', $this->checkOutDate);
                    });
            })
            ->exists();

        // return !$overlap; // If there's an overlap, the rule fails
        if ($overlap) {
            $fail("The room is not available for the given dates.");
        }
    }
}
