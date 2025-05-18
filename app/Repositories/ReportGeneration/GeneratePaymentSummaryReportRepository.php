<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction\Payment;
use App\Models\User;
use Carbon\Carbon;

class GeneratePaymentSummaryReportRepository
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
            return response()->json([
                'error' => 'Missing start or end date'
            ], 400);
        }

        $payments = Payment::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        $summary = [
            'cash' => 0,
            'creditCard' => 0,
            'gcash' => 0,
            'other' => 0,
        ];

        foreach ($payments as $payment) {
            $type = strtolower($payment->payment_type);

            if ($type === 'cash') {
                $summary['cash'] += $payment->amount_received;
            }

            elseif ($type === 'credit_card') {
                $summary['creditCard'] += $payment->amount_received;
            }

            elseif ($type === 'gcash') {
                $summary['gcash'] += $payment->amount_received;
            }

            else {
                $summary['other'] += $payment->amount_received;
            }
        }

        return response()->json(['summary' => $summary]);
    }
}
