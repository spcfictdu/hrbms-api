<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction\Payment;
use App\Models\CashierSession\CashierSession;
use App\Models\User;
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
                    'type' => 'payment',
                    'amount' => $payment->amount_received,
                    'method' => $payment->payment_type,
                    'timestamp' => $payment->created_at->timezone('Asia/Manila')->toDateTimeString(),
                ];                
            }

            $userReports[] = [
                'user' => $user->username,
                'openingBalance' => $session->opening_balance,
                'closingBalance' => $session->closing_balance,
                'openedAt' => $session->opened_at,
                'closedAt' => $session->closed_at,
                'transactions' => $transactions,
            ];
        }

        return [
            'message' => 'Daily cashier report retrieved successfully',
            'data' => $userReports,
        ];
    }
}
