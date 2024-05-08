<?php

namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;

use App\Models\User;

class LogoutRepository extends BaseRepository
{
    public function execute(){

        $user = User::find($this->user()->id);

        $user->update([
            'token' => null
        ]);

        $this->invalidateToken();

        return $this->success("Logged out successfully");
    }
}
