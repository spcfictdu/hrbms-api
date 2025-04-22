<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GenerateDailyReservationsReportRepository
{
    public function execute(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $checkIns = DB::table('transactions')
            ->join('guests', 'transactions.guest_id', '=', 'guests.id')
            ->join('rooms', 'transactions.room_id', '=', 'rooms.id')
            ->whereDate('transactions.check_in_date', $date)
            ->whereNull('transactions.deleted_at')
            ->select('guests.full_name as guestName', 'rooms.room_number as roomNumber', 'transactions.check_in_date as checkInDate')
            ->get();

        $checkOuts = DB::table('transactions')
            ->join('guests', 'transactions.guest_id', '=', 'guests.id')
            ->join('rooms', 'transactions.room_id', '=', 'rooms.id')
            ->whereDate('transactions.check_out_date', $date)
            ->whereNull('transactions.deleted_at')
            ->select('guests.full_name as guestName', 'rooms.room_number as roomNumber', 'transactions.check_out_date as checkOutDate')
            ->get();

        $inHouse = DB::table('transactions')
            ->join('guests', 'transactions.guest_id', '=', 'guests.id')
            ->join('rooms', 'transactions.room_id', '=', 'rooms.id')
            ->whereDate('transactions.check_in_date', '<=', $date)
            ->whereDate('transactions.check_out_date', '>', $date)
            ->whereNull('transactions.deleted_at')
            ->select(
                'guests.full_name as guestName',
                'rooms.room_number as roomNumber',
                'transactions.check_in_date as checkInDate',
                'transactions.check_out_date as checkOutDate'
            )
            ->get();

        return response()->json([
            'message' => 'Daily hotel activity summary retrieved successfully.',
            'data' => [
                'date' => $date,
                'checkIns' => $checkIns,
                'checkOuts' => $checkOuts,
                'inHouse' => $inHouse,
            ]
        ]);
    }
}
