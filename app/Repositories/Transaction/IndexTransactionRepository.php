<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction;

class IndexTransactionRepository extends BaseRepository
{
    public function execute($request)
    {
        $firstNameFilter = $request->input('firstName');
        $middleNameFilter = $request->input('middleName');
        $lastNameFilter = $request->input('lastName');
        $referenceNumberFilter = $request->input('referenceNumber');
        $checkInDateFilter = $request->input('checkInDate');
        $checkOutDateFilter = $request->input('checkOutDate');

        // Pagination
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);


        // Query
        $transactionQuery = Transaction::query();


        if ($firstNameFilter) {
            $transactionQuery->whereHas('guest', function ($query) use ($firstNameFilter) {
                $query->where('first_name', 'like', '%' . $firstNameFilter . '%');
            });
        }

        if ($middleNameFilter) {
            $transactionQuery->whereHas('guest', function ($query) use ($middleNameFilter) {
                $query->where('middle_name', 'like', '%' . $middleNameFilter . '%');
            });
        }

        if ($lastNameFilter) {
            $transactionQuery->whereHas('guest', function ($query) use ($lastNameFilter) {
                $query->where('last_name', 'like', '%' . $lastNameFilter . '%');
            });
        }

        if ($referenceNumberFilter) {
            $transactionQuery->where('reference_number', 'like', '%' . $referenceNumberFilter . '%');
        }

        if ($checkInDateFilter) {
            $transactionQuery->where('check_in_date', 'like', '%' . $checkInDateFilter . '%');
        }

        if ($checkOutDateFilter) {
            $transactionQuery->where('check_out_date', 'like', '%' . $checkOutDateFilter . '%');
        }

        $transactions = $transactionQuery->paginate($perPage);

        $transformedTransactions = $transactions->map(function ($transaction) {
            return [
                "fullName" => $transaction->guest->full_name,
                "status" => $transaction->status,
                "transactionRefNum" => $transaction->reference_number,
                "occupants" => $transaction->number_of_guest,
                "checkInDate" => $transaction->check_in_date,
                "checkOutDate" => $transaction->check_out_date,
                "booked" => $transaction->created_at->format('Y-m-d'),
                "room" => $transaction->room->room_number,
                "total" => 3000  //Temp price for now
            ];
        });

        return $this->success("List of all transactions.", [
            'data' => $transformedTransactions,
            'current_page' => $transactions->currentPage(),
            'from' => $transactions->firstItem(),
            'last_page' => $transactions->lastPage(),
            'per_page' => $transactions->perPage(),
            'to' => $transactions->lastItem(),
            'total' => $transactions->total(),
        ]);
    }
}
