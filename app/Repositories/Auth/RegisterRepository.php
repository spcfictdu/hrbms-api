<?php

namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;

use App\Models\User;

class RegisterRepository extends BaseRepository
{
    public function execute($request)
    {

        $newUser = User::create([
            'username' => $request->username,
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $newUser->assignRole($request->role);

        return $this->success("User created successfully", [
            'username' => $newUser->username,
            'firstName' => $newUser->first_name,
            'lastName' => $newUser->last_name,
            'email' => $newUser->email,
            'role' => $newUser->getRoleNames()->first()
        ]);
    }
}
