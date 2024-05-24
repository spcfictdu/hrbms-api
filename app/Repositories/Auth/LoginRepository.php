<?php

namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginRepository extends BaseRepository
{
    public function execute($request)
    {
        if (
            Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])
        ) {

            $user = User::find($this->user()->id);

            // $token = $this->generateToken($user);

            $token = $user->createToken(
                $request->username,
                ['*'],
                now()->addWeek()
            )->plainTextToken;

            // $user->update([
            //     'token' => $token
            // ]);
        } else {

            return $this->error("Incorrect login credentials", 401);
        }

        return $this->success("Login successful", [
            'username' => $user->username,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            // 'token' => $user->token,
            'token' => $token,
            'role' => $user->getRoleNames()->first()
        ]);
    }
}
