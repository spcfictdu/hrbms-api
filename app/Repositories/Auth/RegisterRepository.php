<?php

namespace App\Repositories\Auth;

use App\Models\Guest\Guest;
use App\Repositories\BaseRepository;
use App\Models\User;

class RegisterRepository extends BaseRepository
{
    public function execute($request)
    {
        $newUser = $this->createUser($request);

        if ($this->isAdminOrFrontDesk($request->role)) {
            return $this->success("User registered successfully", $this->formatUserResponse($newUser), 201);
        }

        $guest = $this->createGuest($request, $newUser);

        return $this->success("User registered successfully", $this->formatGuestResponse($guest, $newUser), 201);
    }

    private function isAdminOrFrontDesk($role)
    {
        return in_array($role, ["ADMIN", "FRONT DESK"]);
    }

    private function createUser($request)
    {
        $user = User::create([
            'username' => $request->username ?? null,
            'first_name' => strtoupper($request->firstName),
            'last_name' => strtoupper($request->lastName),
            'email' => $request->email,
            'password' => $request->password,
            'mobile' => $request->mobile ?? null,
        ]);

        $user->assignRole($request->role);

        return $user;
    }

    private function createGuest($request, $user)
    {
        return Guest::create([
            'reference_number' => $this->guestReferenceNumber(),
            'first_name' => strtoupper($request->firstName),
            'middle_name' => strtoupper($request->middleName) ?? null,
            'last_name' => strtoupper($request->lastName),
            'phone_number' => $request->mobileNumber,
            'email' => $request->email,
            'user_id' => $user->id,
        ]);
    }

    private function formatUserResponse($user)
    {
        return [
            'username' => $user->username,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(),
        ];
    }

    private function formatGuestResponse($guest, $user)
    {
        return [
            'firstName' => $guest->first_name,
            'middleName' => $guest->middle_name ?? null,
            'lastName' => $guest->last_name,
            'mobileNumber' => $guest->phone_number,
            'email' => $guest->email,
            'role' => $user->getRoleNames()->first(),
        ];
    }
}
