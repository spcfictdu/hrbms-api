<?php

namespace App\Repositories\Transaction\Miscellaneous;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\{Arr, Str};

use Carbon\Carbon;


class ShowFormTransactionRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $room = Room::where('reference_number', $referenceNumber)->first();
        $roomType = $room->roomType;
        $rate = $roomType->rates->first();
        $guests = Guest::all();
        $day = Str::lower(Carbon::now()->format('l'));

        if ($room) {
            return $this->success("Transaction Form existing info.", [
                "bookingSummary" => [
                    "roomName" => $roomType->name,
                    "capacity" => $roomType->capacity,
                    "roomTotal" => $rate->$day
                ]
            ]);
        }
        // try{

        // } catch (\Exception $e) {
        //     return $this->error("Error: " . $e->getMessage(), 500, [], false);
        // }
    }
}
