<?php

namespace App\Repositories\ReportGeneration;

use App\Models\Room\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GenerateRoomOccupancyReportRepository
{
    public function execute()
    {
        if (!Auth::user()->hasRole('ADMIN') && !Auth::user()->hasRole('FRONT DESK')) {
            return [
                'message' => 'Unauthorized access.',
                'data' => []
            ];
        }

        $today = Carbon::now()->toDateString();

        $occupied = Room::with(['transactions.guest'])
            ->where('status', 'OCCUPIED')
            ->whereHas('transactions', fn ($q) => $q->whereNull('deleted_at'))
            ->get()
            ->map(function ($room) {
                $guest = optional($room->transactions->last()->guest)->full_name;
                return [
                    'roomNumber' => $room->room_number
                ];
            });

        $reserved = Room::with(['transactions.guest'])
            ->where('status', 'RESERVED')
            ->whereHas('transactions', fn ($q) => $q->whereNull('deleted_at'))
            ->get()
            ->map(function ($room) {
                $transaction = $room->transactions->last();
                return [
                    'roomNumber' => $room->room_number
                ];
            });

        $available = Room::where('status', 'AVAILABLE')
            ->get()
            ->map(fn ($room) => ['roomNumber' => $room->room_number]);

        $unclean = Room::where('status', 'UNCLEAN')
            ->get()
            ->map(fn ($room) => ['roomNumber' => $room->room_number]);

        $unallocated = Room::whereNotIn('status', ['AVAILABLE', 'OCCUPIED'])
            ->whereHas('transactions', function ($q) use ($today) {
                $q->whereDate('check_out_date', '<=', $today)
                  ->whereNull('deleted_at');
            })
            ->distinct()
            ->get()
            ->map(fn ($room) => ['roomNumber' => $room->room_number]);

        return [
            'message' => 'Room occupancy report generated successfully.',
            'data' => [
                'occupied' => $occupied,
                'reserved' => $reserved,
                'available' => $available,
                'unclean' => $unclean,
                'unallocated' => $unallocated,
            ],
        ];
    }
}
