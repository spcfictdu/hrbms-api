<?php

namespace App\Repositories\Guest;

use App\Models\Guest\Guest;
use App\Repositories\BaseRepository;

class IndexGuestRepository extends BaseRepository
{
    public function execute($request)
    {
        $referenceNumberFilter = $request->input('referenceNumber');
        $firstNameFilter = $request->input('firstName');
        $middleNameFilter = $request->input('middleName');
        $lastNameFilter = $request->input('lastName');
        $fullNameFilter = $request->input('fullName');
        $emailFilter = $request->input('email');
        $phoneNumberFilter = $request->input('phoneNumber');

        // Pagination
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');


        // Query
        $guestQuery = Guest::query();

        if ($referenceNumberFilter) {
            $guestQuery->where('reference_number', 'like', '%' . $referenceNumberFilter . '%');
        }


        if ($firstNameFilter) {
            $guestQuery->where('first_name', 'like', '%' . $firstNameFilter . '%');
        }

        if ($middleNameFilter) {
            $guestQuery->where('middle_name', 'like', '%' . $middleNameFilter . '%');
        }

        if ($lastNameFilter) {
            $guestQuery->where('last_name', 'like', '%' . $lastNameFilter . '%');
        }

        if ($fullNameFilter) {
            $guestQuery->where('full_name', 'like', '%' . $fullNameFilter . '%');
        }

        if ($emailFilter) {
            $guestQuery->where('email', 'like', '%' . $emailFilter . '%');
        }

        if ($phoneNumberFilter) {
            $guestQuery->where('phone_number', 'like', '%' . $phoneNumberFilter . '%');
        }


        // return $guestQuery->get();
        $guestQuery->orderBy($sortBy, $sortOrder);

        $guests = $guestQuery->paginate($perPage);

        $transformedGuests = $guests->transform(function ($guest) {
            return [
                'id' => $guest->id,
                'referenceNumber' => $guest->reference_number,
                'fullName' => $guest->full_name,
                'email' => $guest->email,
                'phone' => $guest->phone_number,
            ];
        });

        return $this->success("Successfully retrieved guests", [
            'guests' => $transformedGuests,
            'pagination' => [
                'total' => $guests->total(),
                'perPage' => $guests->perPage(),
                'currentPage' => $guests->currentPage(),
                'lastPage' => $guests->lastPage(),
                'from' => $guests->firstItem(),
                'to' => $guests->lastItem(),
            ]
            // 'pagination' => [
            //     'total' => $rooms->total(),
            //     'perPage' => $rooms->perPage(),
            //     'currentPage' => $rooms->currentPage(),
            //     'lastPage' => $rooms->lastPage(),
            //     'from' => $rooms->firstItem(),
            //     'to' => $rooms->lastItem()
            // ],
        ]);
    }
}
