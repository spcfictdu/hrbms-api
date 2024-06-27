<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

// * REQUEST
use App\Http\Requests\Auth\{
    LoginRequest,
    RegisterRequest,
    LogoutRequest,
    GuestLoginRequest
};

// * REPOSITORY
use App\Repositories\Auth\{
    LoginRepository,
    RegisterRepository,
    LogoutRepository,
    GuestLoginRepository
};

class AuthController extends Controller
{
    protected $login, $register, $logout, $guestLogin;

    public function __construct(
        LoginRepository $login,
        RegisterRepository $register,
        LogoutRepository $logout,
        GuestLoginRepository $guestLogin
    ) {
        $this->login = $login;
        $this->register = $register;
        $this->logout = $logout;
        $this->guestLogin = $guestLogin;
    }

    protected function login(LoginRequest $request)
    {
        return $this->login->execute($request);
    }

    protected function register(RegisterRequest $request)
    {
        return $this->register->execute($request);
    }

    protected function logout(LogoutRequest $request)
    {
        return $this->logout->execute($request);
    }

    protected function guestLogin(GuestLoginRequest $request){
        return $this->guestLogin->execute($request);
    }
}
