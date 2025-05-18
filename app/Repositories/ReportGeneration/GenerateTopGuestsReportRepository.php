<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Transaction;
use App\Models\User;
use Carbon\Carbon;

class GenerateTopGuestsReportRepository
{
    public function execute(Request $request)
    {
        if (!Auth::user()->hasRole('ADMIN') && !Auth::user()->hasRole('FRONT DESK')) {
            return [
                'message' => 'Unauthorized access.',
                'data' => []
            ];
        }

        $startDate = $request->query('start');
        $endDate = $request->query('end');

        if (!$startDate || !$endDate) {
            return [
                'error' => 'Missing start or end date',
                'status' => 400
            ];
        }

        $transactions = Transaction::with('guest')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        $guestSpending = [];
        foreach ($transactions as $transaction) {
            $guestId = $transaction->guest_id;
            if (!isset($guestSpending[$guestId])) {
                $guestSpending[$guestId] = [
                    'guestId' => $guestId,
                    'guestName' => $transaction->guest->full_name ?? 'Unknown',
                    'totalSpent' => 0
                ];
            }
            
            $totalSpent = Payment::where('transaction_id', $transaction->id)->sum('amount_received');
            $guestSpending[$guestId]['totalSpent'] += $totalSpent;
        }

        usort($guestSpending, function ($a, $b) {
            return $b['totalSpent'] <=> $a['totalSpent'];
        });

        return [
            'topGuests' => [
                'guests' => array_values($guestSpending)
            ]
        ];
    }
}