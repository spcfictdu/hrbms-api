<?php

namespace App\Repositories\Auth;

use App\Models\Guest\Guest;
use App\Repositories\BaseRepository;

use App\Models\User;

class RegisterRepository extends BaseRepository
{
    public function execute($request)
    {
    if($request->role === "ADMIN" || $request->role == "FRONT DESK"){
        $newUser = User::create([
            'username' => $request->username,
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => $request->password,
            'mobile' => $request->mobile,
        ]);

        $newUser->assignRole($request->role);

        return $this->success("User created successfully", [
            'username' => $newUser->username,
            'firstName' => $newUser->first_name,
            'lastName' => $newUser->last_name,
            'email' => $newUser->email,
            'role' => $newUser->getRoleNames()->first()
        ]);
    } else {

        $newUser = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        $newUser->assignRole($request->role);
        $guest = Guest::create([
            'reference_number' => $this->guestReferenceNumber(),
            'first_name' => $request->firstName,
            "middle_name" => $request->middleName ?? null,
            "last_name" => $request->lastName,
            "phone_number" => $request->mobileNumber,
            "email" => $request->email,
            "user_id" => $newUser->id
        ]);


        return $this->success("User created successfully", [
            'firstName' => $guest->first_name,
            'middleName' => $guest->middle_name ?? null,
            'lastName' => $guest->last_name,
            'mobileNumber' => $guest->phone_number,
            'email' => $guest->email,
            'role' => $newUser->getRoleNames()->first()
        ]);
    }

    }
}
