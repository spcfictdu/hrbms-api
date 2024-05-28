<?php

namespace App\Repositories\Transaction\Guest;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\{Arr, Str};

use Carbon\Carbon;


class IndexGuestRepository extends BaseRepository
{
    public function execute()
    {
        // Return all guests
        $guests = Guest::all()->map(function ($guest) {
            return [
                'id' => $guest->id,
                'referenceNumber' => $guest->reference_number,
                'fullName' => $guest->full_name,
            ];
        });

        return $this->success('Guests retrieved successfully.', $guests);
    }
}
