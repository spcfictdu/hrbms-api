<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction\Transaction;
use Carbon\Carbon;

class GenerateDailyReservationsReportRepository
{
    public function execute(Request $request)
    {
        if (!Auth::user()->hasRole('ADMIN') && !Auth::user()->hasRole('FRONT DESK')) {
            return response()->json(['message' => 'Not authorized']);
        }

        $date = $request->query('date', now()->toDateString());

        $expectedCheckIns = Transaction::with(['guest', 'room'])
            ->whereDate('check_in_date', $date)
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($transaction) {
                return [
                    'guestName' => optional($transaction->guest)->full_name,
                    'roomNumber' => optional($transaction->room)->room_number,
                    'checkInDate' => $transaction->check_in_date,
                ];
            });
        
        $actualCheckIns = Transaction::with(['guest', 'room'])
            ->whereDate('check_in_date', $date)
            ->where('status', 'CHECKED-IN')
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($transaction) {
                return [
                    'guestName' => optional($transaction->guest)->full_name,
                    'roomNumber' => optional($transaction->room)->room_number,
                    'checkInDate' => $transaction->check_in_date,
                    'checkInTime' => $transaction->transactionHistory->check_in_time
                ];
            });

        $expectedCheckOuts = Transaction::with(['guest', 'room'])
            ->whereDate('check_out_date', $date)
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($transaction) {
                return [
                    'guestName' => optional($transaction->guest)->full_name,
                    'roomNumber' => optional($transaction->room)->room_number,
                    'checkOutDate' => $transaction->check_out_date,
                ];
            });

        $actualCheckOuts = Transaction::with(['guest', 'room'])
            ->whereDate('check_out_date', $date)
            ->where('status', 'CHECKED-OUT')
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($transaction) {
                return [
                    'guestName' => optional($transaction->guest)->full_name,
                    'roomNumber' => optional($transaction->room)->room_number,
                    'checkOutDate' => $transaction->check_out_date,
                    'checkOutTime' => $transaction->transactionHistory->check_out_time
                ];
            });

        $inHouse = Transaction::with(['guest', 'room'])
            ->whereDate('check_in_date', '<=', $date)
            ->whereDate('check_out_date', '>', $date)
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($transaction) {
                return [
                    'guestName' => optional($transaction->guest)->full_name,
                    'roomNumber' => optional($transaction->room)->room_number,
                    'checkInDate' => $transaction->check_in_date,
                    'checkOutDate' => $transaction->check_out_date,
                ];
            });

        return response()->json([
            'message' => 'Daily hotel activity summary retrieved successfully.',
            'data' => [
                'date' => $date,
                'expectedCheckIns' => $expectedCheckIns,
                'actualCheckIns' => $actualCheckIns,
                'expectedCheckOuts' => $expectedCheckOuts,
                'actualCheckOuts' => $actualCheckOuts,
                'inHouse' => $inHouse,
            ]
        ]);
    }
}
