<?php

namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;

use App\Models\User;

class LogoutRepository extends BaseRepository
{
    public function execute($request)
    {
        // $user = User::find($this->user()->id);
        $user = $request->user();

        // $user->update([
        //     'token' => null
        // ]);

        // $this->invalidateToken();

        $request->user()->currentAccessToken()->delete();

        return $this->success("Logged out successfully");
    }
}
