<?php

namespace App\Repositories\Guest\Miscellaneous;

use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Repositories\BaseRepository;

use App\Models\Guest\Guest;

class UpdateAccountInfoRepository extends BaseRepository
{
    public function execute($request)
    {
        // return $this->user();
        if ($this->user()->getRoleNames()->first() == "GUEST") {
            Guest::where('user_id', $this->user()->id)->first()->update([
                "first_name" => mb_strtoupper($request->firstName),
                "middle_name" => mb_strtoupper($request->middleName) ?? null,
                "last_name" => mb_strtoupper($request->lastName),
                "email" => $request->email,
                "phone_number" => $request->phoneNumber,
                "province" => $request->province,
                "city" => $request->city,
            ]);

            User::where('id', $this->user()->id)->first()->update([
                "first_name" => mb_strtoupper($request->firstName),
                "last_name" => mb_strtoupper($request->lastName),
                "email" => $request->email,
            ]);

            return $this->success("User Details Updated Successfully");
        } else {
            return $this->error("Guest not found.");
        }
    }
}
