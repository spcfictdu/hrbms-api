<?php

namespace App\Repositories\Guest\Miscellaneous;

use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;
use App\Models\Guest\Guest;

class UpdateAccountInfoRepository extends BaseRepository
{
    public function execute($request)
    {
        // return $this->user();
        if ($this->user()->getRoleNames()->first() == "GUEST") {
            Guest::where('user_id', $this->user()->id)->first()->update([
                "first_name" => Str::of($request->firstName)->upper(),
                "middle_name" => Str::of($request->middleName)->upper() ?? null,
                "last_name" => Str::of($request->lastName)->upper(),
                "email" => $request->email,
                "phone_number" => $request->phoneNumber,
                "province" => $request->province,
                "city" => $request->city,
            ]);

            User::where('id', $this->user()->id)->first()->update([
                "first_name" => Str::of($request->firstName)->upper(),
                "last_name" => Str::of($request->lastName)->upper(),
                "email" => $request->email,
            ]);

            return $this->success("User Details Updated Successfully");
        } else {
            return $this->error("Guest not found.");
        }
    }
}
