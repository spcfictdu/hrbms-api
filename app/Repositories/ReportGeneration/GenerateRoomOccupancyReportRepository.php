<?php

namespace App\Repositories\ReportGeneration;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GenerateRoomOccupancyReportRepository
{
    public function execute(Request $request)
    {
        $today = now()->toDateString();

        // Occupied rooms with guest info
        $occupied = DB::table('rooms')
            ->join('transactions', 'rooms.id', '=', 'transactions.room_id')
            ->join('guests', 'transactions.guest_id', '=', 'guests.id')
            ->where('rooms.status', 'OCCUPIED')
            ->whereNull('transactions.deleted_at')
            ->select('rooms.room_number', 'guests.full_name as guest')
            ->get()
            ->map(function ($room) {
                return [
                    'roomNumber' => $room->room_number,
                    'guest' => $room->guest
                ];
            });

        // Reserved rooms from transactions table
        $reserved = DB::table('rooms')
            ->join('transactions', 'rooms.id', '=', 'transactions.room_id')
            ->join('guests', 'transactions.guest_id', '=', 'guests.id')
            ->where('rooms.status', 'RESERVED')
            ->whereNull('transactions.deleted_at')
            ->select('rooms.room_number', 'guests.full_name as guest', 'transactions.check_in_date')
            ->get()
            ->map(function ($room) {
                return [
                    'roomNumber' => $room->room_number,
                    'guest' => $room->guest,
                    'reservationDate' => $room->check_in_date
                ];
            });

        // Available rooms
        $available = DB::table('rooms')
            ->where('status', 'AVAILABLE')
            ->select('room_number')
            ->get()
            ->map(function ($room) {
                return [
                    'roomNumber' => $room->room_number
                ];
            });

        // Unclean rooms
        $unclean = DB::table('rooms')
            ->where('status', 'UNCLEAN')
            ->select('room_number')
            ->get()
            ->map(function ($room) {
                return [
                    'roomNumber' => $room->room_number
                ];
            });

        // Unallocated rooms: checked out today or earlier, but not yet marked AVAILABLE or OCCUPIED
        $unallocated = DB::table('rooms')
            ->join('transactions', 'rooms.id', '=', 'transactions.room_id')
            ->whereNull('transactions.deleted_at')
            ->whereDate('transactions.check_out_date', '<=', $today)
            ->whereNotIn('rooms.status', ['AVAILABLE', 'OCCUPIED'])
            ->select('rooms.room_number')
            ->distinct()
            ->get()
            ->map(function ($room) {
                return [
                    'roomNumber' => $room->room_number
                ];
            });

        return response()->json([
            'message' => 'Room occupancy report generated successfully.',
            'data' => [
                'occupied' => $occupied,
                'reserved' => $reserved,
                'available' => $available,
                'unallocated' => $unallocated,
                'unclean' => $unclean,
            ]
        ]);
    }
}
