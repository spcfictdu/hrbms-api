<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait UserAuth
{
    protected function user(){

        return Auth::user();

    }

    protected function generateToken($user){

        $token = $user->createToken(bin2hex(random_bytes(10)))->plainTextToken;

        return $token;
    }

    protected function invalidateToken(){

        Auth::user()->tokens()->delete();
    }
}
