<?php

namespace App\Repositories\Enum;

use App\Models\Guest\Guest;
use App\Models\Room\Room;
use App\Repositories\BaseRepository;

class GuestEnumRepository extends BaseRepository
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
