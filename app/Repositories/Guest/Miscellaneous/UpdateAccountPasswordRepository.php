<?php

namespace App\Repositories\Guest\Miscellaneous;

use App\Models\Transaction\Transaction;
use App\Models\User;
use App\Repositories\BaseRepository;

use App\Models\Guest\Guest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateAccountPasswordRepository extends BaseRepository
{
    public function execute($request)
    {
        // return $this->user();
        $user = auth()->user();

        if ($this->user()->getRoleNames()->first() !== "GUEST") {
            return $this->error("Guest not found");
        }

        if (Hash::check($request->oldPassword, $user->password)) {
            $user->password = Hash::make($request->newPassword);
            $user->save();

            return $this->success("User Details Updated Successfully");
        } else {
            return $this->error("Wrong old Password");
        }
    }
}
