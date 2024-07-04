<?php

namespace App\Repositories\Guest\Miscellaneous;

use App\Models\Transaction\Transaction;
use App\Repositories\BaseRepository;

use App\Models\Guest\Guest;

class EditAccountInfoRepository extends BaseRepository
{
    public function execute()
    {
        // return $this->user();
        if ($this->user()->getRoleNames()->first() == "GUEST" || $this->user()->getRoleNames()->first() == "ADMIN") {
            $guest = Guest::where('user_id', $this->user()->id)->first();

            return $this->success("Guest Current Credentials", [
                "firstName" => $guest->first_name,
                "middleName" => $guest->middle_name,
                "lastName" => $guest->last_name,
                "email" => $guest->email,
                "phoneNumber" => $guest->phone_number,
            ]);
        } else {
            return $this->error("Guest not found.");
        }
    }
}
