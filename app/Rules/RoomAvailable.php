<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class RoomAvailable implements ValidationRule
{
    protected $checkInTime;
    protected $checkOutTime;

    public function __construct($checkInTime, $checkOutTime)
    {
        $this->checkInTime = $checkInTime;
        $this->checkOutTime = $checkOutTime;
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
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereBetween('check_in_time', [$this->checkInTime, $this->checkOutTime])
                        ->orWhereBetween('check_out_time', [$this->checkInTime, $this->checkOutTime]);
                })
                    ->orWhere(function ($q) {
                        $q->where('check_in_time', '<=', $this->checkInTime)
                            ->where('check_out_time', '>=', $this->checkOutTime);
                    });
            })
            ->exists();

        // return !$overlap; // If there's an overlap, the rule fails
        if ($overlap) {
            $fail("The room is not available for the given dates and times.");
        }
    }
}
