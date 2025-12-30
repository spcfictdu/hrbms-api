<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomAvailable implements ValidationRule
{
    protected $checkInTime, $checkOutTime, $checkInDate, $checkOutDate;

    public function __construct($checkInTime, $checkOutTime, $checkInDate, $checkOutDate)
    {
        $this->checkInTime = $checkInTime;
        $this->checkOutTime = $checkOutTime;
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
        $existingBookings = DB::table('transactions')
            ->where('room_id', $roomId)
            ->whereNull('deleted_at')
            ->whereDate('check_in_date', '<=', $this->checkInDate)
            ->whereDate('check_out_date', '>=', $this->checkInDate)
            ->get(['check_in_date', 'check_out_date', 'check_in_time', 'check_out_time']);

        $bookingCheckIn = Carbon::parse($this->checkInDate . ' ' . $this->checkInTime);
        $bookingCheckOut = Carbon::parse($this->checkOutDate . ' ' . $this->checkOutTime);

        // Check time overlap only if date overlaps
        foreach ($existingBookings as $booking) {
            $existingCheckIn = Carbon::parse($booking->check_in_date . ' ' . $booking->check_in_time)->subHours(2);
            $existingCheckOut = Carbon::parse($booking->check_out_date . ' ' . $booking->check_out_time)->addHours(2);

            $overlaps = (
                ($bookingCheckIn >= $existingCheckIn && $bookingCheckIn < $existingCheckOut) ||
                ($bookingCheckOut > $existingCheckIn && $bookingCheckOut <= $existingCheckOut) ||
                ($bookingCheckIn <= $existingCheckIn && $bookingCheckOut >= $existingCheckOut)
            );

            if ($overlaps) {
                $fail("The room is not available for the given time slot on this date. {$existingCheckIn} - {$existingCheckOut}");
                return;
            }
        }

        // // return !$overlap; // If there's an overlap, the rule fails
        // if ($overlap) {
        //     $fail("The room is not available for the given dates and times.");
        // }
    }
}
