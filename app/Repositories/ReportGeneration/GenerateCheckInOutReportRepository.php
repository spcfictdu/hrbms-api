<?php

namespace App\Repositories\ReportGeneration;

use App\Repositories\BaseRepository;

use Carbon\Carbon;

use App\Models\Transaction\Transaction;

class GenerateCheckInOutReportRepository extends BaseRepository
{
    public function execute($request)
    {

        $dateFrom = $request->dateFrom;
    $dateTo = $request->dateTo;

    $transactions = Transaction::query();

    if ($dateFrom && $dateTo) {
        // Both dates are provided
        $transactions->where('check_in_date', '<=', $dateTo)
                     ->where('check_out_date', '>=', $dateFrom);
    } elseif ($dateFrom) {
        // Only dateFrom is provided
        $transactions->where('check_in_date', '<=', $dateFrom);
    } elseif ($dateTo) {
        // Only dateTo is provided
        $transactions->where('check_out_date', '>=', $dateTo);
    }

    $transactions = $transactions->get();

        $response = [];

        foreach($transactions as $transaction) {

            $guest = $transaction->guest;

            array_push($response, [
                "referenceNumber" => $transaction->reference_number,
                "guestFullName" => $guest->middle_name ? "{$guest->last_name}, {$guest->first_name} {$guest->middle_name}" : "{$guest->last_name}, {$guest->first_name}",
                "roomNumber" => $transaction->room->room_number,
                "totalAmount" => $transaction->payment->amount_received,
                "checkIn" => Carbon::parse($transaction->check_in_date . ' ' . $transaction->check_in_time)->format('Y-m-d h:i A'),
                "checkOut" => Carbon::parse($transaction->check_out_date . ' ' . $transaction->check_out_time)->format('Y-m-d h:i A')
            ]);
        }

        return $this->success("Payment report list.", $response);
    }
}
