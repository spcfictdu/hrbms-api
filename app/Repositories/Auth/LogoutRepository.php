<?php

namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;
use Laravel\Sanctum\TransientToken;

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

        if ($user->tokens()->count() > 0) {
            $user->tokens()->delete(); // Delete all personal access tokens
        }

        // $token = $user->currentAccessToken();

        // if ($token) {
        //     $token->delete(); // Only delete if a token exists
        // }

        return $this->success("Logged out successfully");
    }
}
