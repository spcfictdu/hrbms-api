<?php

namespace App\Repositories\ReportGeneration;

use App\Repositories\BaseRepository;

use Carbon\Carbon;

use App\Models\{
    Transaction\Transaction,
    Room\RoomType,
    Room\Room
};

class GenerateRevenueReportRepository extends BaseRepository
{
    public function execute($request)
    {

        if ($request->dateFrom && $request->dateTo) {
            if ($request->roomType) {

                $roomType = RoomType::where('name', $request->roomType)->first();
                $roomTypeTransactions = $roomType->rooms->pluck('transactions')->collapse();

                $transactions = $roomTypeTransactions->where('check_in_date', '>=', $request->dateFrom)
                    ->where('check_in_date', '<=', $request->dateTo)
                    ->where('check_out_date', '<=', Carbon::parse($request->dateTo)->addDay()->toDateString());
            } elseif ($request->roomNumber) {

                $transactions = Transaction::where('check_in_date', '>=', $request->dateFrom)
                    ->where('check_in_date', '<=', $request->dateTo)
                    ->where('check_out_date', '<=', Carbon::parse($request->dateTo)->addDay()->toDateString())
                    ->where('room_id', $this->getRoomIdByRoomNumber($request->roomNumber))
                    ->get();
            } else {

                $transactions = Transaction::where('check_in_date', '>=', $request->dateFrom)
                    ->where('check_in_date', '<=', $request->dateTo)
                    ->where('check_out_date', '<=', Carbon::parse($request->dateTo)->addDay()->toDateString())
                    ->get();
            }
        } else {
            if ($request->roomType) {

                $roomType = RoomType::where('name', $request->roomType)->first();
                $transactions = $roomType->rooms->pluck('transactions')->collapse();
            } elseif ($request->roomNumber) {

                $transactions = Transaction::where('room_id', $this->getRoomIdByRoomNumber($request->roomNumber))->get();
            } else {

                $transactions = Transaction::all();
            }
        }

        $payments = $transactions->pluck('payment')->collapse();
        $dateFrom = Carbon::parse($request->dateFrom);
        $dateTo = Carbon::parse($request->dateTo);
        $daysCount = $dateFrom->diffInDays($dateTo);
        $roomCount = Room::all()->count();
        $bookings = $transactions->whereIn('status', ['CONFIRMED', 'CHECKED-IN', 'CHECKED-OUT'])->count();
        $occupancy = ($bookings / ($roomCount * $daysCount)) * 100;

        if ($request->roomType) {
            $response = [
                'roomType' => $request->roomType,
                'dateFrom' => $request->dateFrom,
                'dateTo' => $request->dateTo,
                'total' => number_format((float)($payments->sum('amount_received')), 2, '.', ''),
                'taxes' => number_format((float)($payments->sum('amount_received') * 0.12), 2, '.', ''),
                'bookings' => $bookings,
                'occupancy' => "{$occupancy}%"
            ];
        } elseif ($request->roomNumber) {
            $response = [
                'roomNumber' => $request->roomNumber,
                'dateFrom' => $request->dateFrom,
                'dateTo' => $request->dateTo,
                'total' => number_format((float)($payments->sum('amount_received')), 2, '.', ''),
                'taxes' => number_format((float)($payments->sum('amount_received') * 0.12), 2, '.', ''),
                'bookings' => $bookings,
                'occupancy' => "{$occupancy}%"
            ];
        } else {
            $response = [
                'dateFrom' => $request->dateFrom,
                'dateTo' => $request->dateTo,
                'total' => number_format((float)($payments->sum('amount_received')), 2, '.', ''),
                'taxes' => number_format((float)($payments->sum('amount_received') * 0.12), 2, '.', ''),
                'bookings' => $bookings,
                'occupancy' => "{$occupancy}%"
            ];
        }

        return $this->success("Revenue report.", $response);


    }
}
