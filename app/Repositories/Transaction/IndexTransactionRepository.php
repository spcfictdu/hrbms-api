<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction;
use App\Models\Transaction\Payment;
use App\Models\Amenity\BookingAddon;

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
        $sortOrder = $request->input('sortOrder', 'desc');


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
            $roomRefund = Transaction::where('id', $transaction->id)
                ->where('payment_status', 'REFUNDED')
                ->first();
            $addonRefund = BookingAddon::where('transaction_id', $transaction->id)
                ->where('payment_status', 'REFUNDED')
                ->sum('total_price');
            $totalRefund = ($roomRefund->room_total ?? 0) + ($addonRefund ?? 0);
            $roomVoided = Transaction::where('id', $transaction->id)
                ->where('payment_status', 'VOIDED')
                ->first();
            $addonVoided = BookingAddon::where('transaction_id', $transaction->id)
                ->where('payment_status', 'VOIDED')
                ->sum('total_price');
            $totalVoided = ($roomVoided->room_total ?? 0) + ($addonVoided ?? 0);
            $fullAddons = BookingAddon::where('transaction_id', $transaction->id)
                ->sum('total_price');
            $totalPayments = Payment::where('transaction_id', $transaction->id)
                ->sum('amount_received');

            return [
                "fullName" => $transaction->guest->full_name,
                "guestId" => $transaction->guest->id,
                "status" => $transaction->status,
                "transactionRefNum" => $transaction->reference_number,
                "occupants" => $transaction->number_of_guest,
                "checkInDate" => $transaction->check_in_date,
                "checkOutDate" => $transaction->check_out_date,
                "booked" => $transaction->created_at->format('Y-m-d'),
                "room" => [
                    "referenceNumber" => $transaction->room->reference_number,
                    "number" => $transaction->room->room_number,
                    "name" => $transaction->room->roomType->name,
                    "capacity" => $transaction->room->roomType->capacity,
                ],
                'total' => number_format((float) ($transaction->room_total + $fullAddons), 2, '.', ''),
                'totalReceived' => $totalPayments,
                'refunded' => number_format((float) $totalRefund, 2, '.', ''),
                'voided' => number_format((float) $totalVoided, 2, '.', ''),
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
