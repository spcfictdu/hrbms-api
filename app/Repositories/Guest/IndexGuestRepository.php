<?php

namespace App\Repositories\Guest;

use App\Models\Guest\Guest;
use App\Repositories\BaseRepository;

class IndexGuestRepository extends BaseRepository
{
    public function execute($request)
    {
        $firstNameFilter = $request->input('firstName');
        $middleNameFilter = $request->input('middleName');
        $lastNameFilter = $request->input('lastName');
        // $lastNameFilter = $request->input('lastName');
        $emailFilter = $request->input('email');
        $phoneNumberFilter = $request->input('phoneNumber');


        // Query
        $guestQuery = Guest::query();


        if ($firstNameFilter) {
            $guestQuery->where('first_name', 'like', '%' . $firstNameFilter . '%');
        }

        if ($middleNameFilter) {
            $guestQuery->where('middle_name', 'like', '%' . $middleNameFilter . '%');
        }

        if ($lastNameFilter) {
            $guestQuery->where('last_name', 'like', '%' . $lastNameFilter . '%');
        }

        if ($emailFilter) {
            $guestQuery->where('email', 'like', '%' . $emailFilter . '%');
        }

        if ($phoneNumberFilter) {
            $guestQuery->where('phone_number', 'like', '%' . $phoneNumberFilter . '%');
        }


        // return $guestQuery->get();

        $guests = $guestQuery->get()->transform(function ($guest) {
            return [
                'id' => $guest->id,
                'fullName' => $guest->full_name,
                'email' => $guest->email,
                'phone' => $guest->phone_number,
            ];
        });

        // return $guests;
        return $this->success("Successfully retrieved guests", $guests);
    }
}
