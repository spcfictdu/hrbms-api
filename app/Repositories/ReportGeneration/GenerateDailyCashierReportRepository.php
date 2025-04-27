<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Transaction;
use App\Models\Room\RoomTypeRate;
use Carbon\Carbon;

class GenerateDailyCashierReportRepository
{
    public function execute(Request $request)
    {
        if(Auth::user()->hasRole('ADMIN') || Auth::user()->hasRole('FRONT DESK')){
            $date = $request->query('date', now()->toDateString());

            $payments = Payment::whereDate('created_at', $date)->get();

            $transactions = Transaction::whereIn('id', $payments->pluck('transaction_id'))
                ->with(['room.roomType', 'user'])
                ->get()
                ->keyBy('id');

            $userReports = [];

            $transactions->each(function ($transaction) use ($date, $payments, &$userReports) {
                $user = $transaction->user;

                if (!$user) {
                    return;
                }

                if (!isset($userReports[$user->username])) {
                    $userReports[$user->username] = [
                        'user' => $user->username,
                        'openingBalance' => 0,
                        'closingBalance' => 0,
                        'openedAt' => Carbon::parse($date . ' 08:00:00'),
                        'closedAt' => Carbon::parse($date . ' 17:00:00'),
                        'transactions' => [],
                    ];
                }

                if ($transaction->room && $transaction->room->roomType) {
                    $rate = RoomTypeRate::where('room_type_id', $transaction->room->roomType->id)
                        ->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date)
                        ->whereNull('deleted_at')
                        ->first();

                    if ($rate) {
                        $dayName = strtolower(Carbon::parse($date)->format('l'));
                        $dailyRate = $rate->$dayName;

                        if ($dailyRate) {
                            $userReports[$user->username]['openingBalance'] += $dailyRate;
                        }
                    }
                }

                $paymentTotal = $payments->where('transaction_id', $transaction->id)->sum('amount_received');
                $userReports[$user->username]['closingBalance'] += $paymentTotal;

                $transactionPayments = $payments->where('transaction_id', $transaction->id);

                $transactionPayments->each(function ($payment) use ($user, &$userReports, $transaction) {
                    $userReports[$user->username]['transactions'][] = [
                        'type' => 'payment',
                        'amount' => $payment->amount_received,
                        'method' => $payment->payment_type,
                        'timestamp' => $payment->created_at,
                        'roomNumber' => $transaction->room?->room_number,
                        'roomType' => $transaction->room?->roomType?->name,
                    ];
                });
            });

            $responseData = array_values($userReports);

            return [
                'message' => 'Daily cashier report retrieved successfully',
                'data' => $responseData
            ];
        }
        else{
            return response()->json(['message' => 'Not authorized']);
        }
    }
}
