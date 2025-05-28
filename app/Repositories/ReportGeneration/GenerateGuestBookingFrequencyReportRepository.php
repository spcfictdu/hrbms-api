<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction\Transaction;
use App\Models\User;
use Carbon\Carbon;

class GenerateGuestBookingFrequencyReportRepository
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

        $bookings = [];
        $guestBooking = [];
        foreach ($transactions as $transaction) {
            $guestId = $transaction->guest_id;  
            if (!isset($guestBooking[$guestId])) {
                $guestBooking[$guestId] = [
                    'guestId' => $guestId,
                    'name' => $transaction->guest->full_name ?? 'Unknown',
                    'bookings' => $bookings[$guestId] = 1
                ];
            }
            else {
                $guestBooking[$guestId]['bookings'] += 1;
            }
        }

        return [
                'frequentGuests' => array_values($guestBooking)
        ];
    }
}