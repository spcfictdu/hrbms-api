<?php

namespace App\Repositories\Guest\Miscellaneous;

use App\Repositories\BaseRepository;

use App\Models\Guest\Guest;

class AccountInfoRepository extends BaseRepository
{
    public function execute()
    {
        // return $this->user();
        if ($this->user()->getRoleNames()->first() == "GUEST") {
            //            $guest = Guest::where('user_id', $)
            return $this->user();
        } else {
            return $this->error("Guest not found.");
        }
    }
}
