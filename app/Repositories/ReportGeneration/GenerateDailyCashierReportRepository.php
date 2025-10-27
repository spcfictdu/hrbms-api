<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction\Payment;
use App\Models\CashierSession\CashierSession;
use App\Models\User;
use App\Models\Transaction\VoidRefund;
use Carbon\Carbon;

class GenerateDailyCashierReportRepository
{
    public function execute(Request $request)
    {
        if (!Auth::user()->hasRole('ADMIN') && !Auth::user()->hasRole('FRONT DESK')) {
            return [
                'message' => 'Unauthorized access.',
                'data' => []
            ];
        }

        $date = $request->query('date', now()->toDateString());

        $cashierSessions = CashierSession::whereDate('opened_at', $date)
            ->with('user')
            ->get();

        $cashiers = User::Role('FRONT DESK')
            ->orderBy('id', 'asc')
            ->get();

        $cashierIds = [];
        $i = 1;

        foreach ($cashiers as $cashier) {
            $cashierIds[(string)$cashier->id] = $i;
            $i += 1;
        }

        $userReports = [];

        foreach ($cashierSessions as $session) {
            $user = $session->user;

            if (!$user) {
                continue;
            }

            $payments = Payment::where('cashier_session_id', $session->id)
                ->whereDate('created_at', $date)
                ->get();

            $transactions = [];

            foreach ($payments as $payment) {
                $transactions[] = [
                    'userId' => $payment->user->id,
                    'username' => $payment->user->username,
                    'transactionReferenceNumber' => $payment->transaction->reference_number,
                    'type' => 'PAYMENT',
                    'amount' => $payment->amount_received,
                    'method' => $payment->payment_type,
                    'timestamp' => $payment->created_at->timezone('Asia/Manila')->toDateTimeString(),
                ];                
            }

            $refunds = VoidRefund::where('cashier_session_id', $session->id)
                ->where('type', 'REFUND')
                ->get();

            $voids = VoidRefund::where('cashier_session_id', $session->id)
                ->where('type', 'VOID')
                ->get();

            $totalRefunded = $refunds->sum('amount');
            $totalVoided = $voids->sum('amount');

            $mergedVoidsRefunds = $refunds->merge($voids);

            $transformedVoidsRefunds = $mergedVoidsRefunds->map(function ($data) {
                return [
                    'userId' => $data->cashierSession->user->id,
                    'username' => $data->cashierSession->user->username,
                    'transactionReferenceNumber' => $data->transaction->reference_number,
                    'type' => $data->type,
                    'amount' => $data->amount,
                    'method' => null,
                    'timestamp' => $data->created_at->timezone('Asia/Manila')->toDateTimeString()
                ];
            });

            $transformedVoidsRefunds = collect($transformedVoidsRefunds);
            $transactions = collect($transactions);

            $mergedTransactions = $transformedVoidsRefunds->merge($transactions);

            $orderedTransactions = $mergedTransactions->groupBy('timestamp', 'asc')
                ->values();

            $userReports[] = [
                'user' => $user->last_name . ', ' . $user->first_name,
                'userId' => $user->id,
                'cashierId' => $cashierIds[(string)$user->id] ?? 'admin',
                'cashierSessionId' => $session->id,
                'openingBalance' => $session->opening_balance,
                'openingAdjustment' => $session->opening_adjustment,
                'closingBalance' => $session->closing_balance,
                'closingAdjustment' => $session->closing_adjustment,
                'totalRefunded' => $totalRefunded,
                'totalVoided' => $totalVoided,
                'openedAt' => $session->opened_at,
                'closedAt' => $session->closed_at,
                'transactions' => $mergedTransactions,
            ];
        }

        return [
            'message' => 'Daily cashier report retrieved successfully',
            'data' => $userReports,
        ];
    }
}
