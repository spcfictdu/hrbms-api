<?php

namespace App\Repositories\ReportGeneration;

use App\Repositories\BaseRepository;

use Carbon\Carbon;

use App\Models\Transaction\Payment;

class GeneratePaymentReportRepository extends BaseRepository
{
    public function execute($request)
    {

        if ($request->dateFrom && $request->dateTo) {
            if ($request->paymentType) {

                $payments = Payment::where('payment_type', $request->paymentType)
                            ->where('created_at', '>=', $request->dateFrom)
                            ->where('created_at', '<=', $request->dateTo)->get();

            } else {

                $payments = Payment::where('created_at', '>=', $request->dateFrom)
                            ->where('created_at', '<=', $request->dateTo)->get();
            }
        } else {
            if ($request->paymentType) {

                $payments = Payment::where('payment_type', $request->paymentType)->get();

            } else {
                $payments = Payment::all();
            }
        }

        $response = [];

        foreach($payments as $payment) {
            $date = Carbon::parse($payment->created_at)->toDateString();
            $time = Carbon::parse($payment->created_at)->format('g:i A');

            $guest = $payment->transaction->guest;

            if($guest->middle_name){
                array_push($response, [
                    "date" => $date,
                    "time" => $time,
                    "email" => $guest->email,
                    "transactionNumber" => $payment->transaction->reference_number,
                    "fullName" => "{$guest->last_name}, {$guest->first_name} {$guest->middle_name}",
                    "paymentType" => $payment->payment_type,
                    "total" => $payment->amount_received
                ]);

            } else {
                array_push($response, [
                    "date" => $date,
                    "time" => $time,
                    "email" => $guest->email,
                    "transactionNumber" => $payment->transaction->reference_number,
                    "fullName" => "{$guest->last_name}, {$guest->first_name}",
                    "paymentType" => $payment->payment_type,
                    "total" => $payment->amount_received
                ]);
            }
        }

        return $this->success("Payment report list.", $response);


    }
}
