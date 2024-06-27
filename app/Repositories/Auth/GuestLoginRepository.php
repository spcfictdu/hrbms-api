<?php


namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GuestLoginRepository extends BaseRepository
{
    public function execute($request)
    {
        if (
            Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])
        ) {

            $user = User::find($this->user()->id);

            // $token = $this->generateToken($user);

            $token = $user->createToken(
                $request->email,
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
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            // 'token' => $user->token,
            'token' => $token,
            'role' => $user->getRoleNames()->first()
        ]);
    }
}
