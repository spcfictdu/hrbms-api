<?php

namespace App\Repositories\Guest;

use App\Models\Guest\Guest;
use App\Repositories\BaseRepository;

class DeleteGuestRepository extends BaseRepository
{
    public function execute($id)
    {
        $guest = Guest::find($id);

        if (!$guest) {
            return $this->error("Guest not found", null, 404);
        }

        $guest->delete();

        return $this->success("Successfully deleted guest", null);
    }
}
