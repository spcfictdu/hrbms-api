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
        $sortBy = $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');


        // Query
        $transactionQuery = Transaction::query();


        if ($firstNameFilter) {
            $transactionQuery->whereHas('guest', function ($query) use ($firstNameFilter) {
                $query->whereNull('deleted_at') // Exclude soft-deleted guests
                    ->where('first_name', 'like', '%' . $firstNameFilter . '%');
            });
        }

        if ($middleNameFilter) {
            $transactionQuery->whereHas('guest', function ($query) use ($middleNameFilter) {
                $query->whereNull('deleted_at') // Exclude soft-deleted guests
                    ->where('middle_name', 'like', '%' . $middleNameFilter . '%');
            });
        }

        if ($lastNameFilter) {
            $transactionQuery->whereHas('guest', function ($query) use ($lastNameFilter) {
                $query->whereNull('deleted_at') // Exclude soft-deleted guests
                    ->where('last_name', 'like', '%' . $lastNameFilter . '%');
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

        $transactionQuery->orderBy($sortBy, $sortOrder);

        $transactions = $transactionQuery->paginate($perPage);

        $filteredTransactions = $transactions->filter(function ($transaction) {
            return !is_null($transaction->guest);
        });

        $transformedTransactions = $filteredTransactions->map(function ($transaction) {
            return [
                "fullName" => $transaction->guest->full_name,
                "status" => $transaction->status,
                "transactionRefNum" => $transaction->reference_number,
                "occupants" => $transaction->number_of_guest,
                "checkInDate" => $transaction->check_in_date,
                "checkOutDate" => $transaction->check_out_date,
                "booked" => $transaction->created_at->format('Y-m-d'),
                "room" => $transaction->room->room_number,
                "total" => $transaction->payment?->amount_received,
            ];
        });

        return $this->success("List of all transactions.", [
            'data' => $transformedTransactions->values()->toArray(),
            'pagination' => [
                'total' => $transactions->total(),
                'perPage' => $transactions->perPage(),
                'currentPage' => $transactions->currentPage(),
                'lastPage' => $transactions->lastPage(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ]
        ]);
    }
}
