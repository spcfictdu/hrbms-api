<?php

namespace App\Repositories\Enum;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\Room\Room;
use App\Models\amenity\Addon;
use App\Models\Discount\Voucher;
use App\Models\Discount\Discount;
use App\Repositories\BaseRepository;
use Illuminate\Support\Number;

class RoomEnumRepository extends BaseRepository
{
    public function execute($request)
    {
        $filterRoomType = $request->input('roomType');
        $filterRoomNumber = $request->input('roomNumber');
        $filterDateRange = explode(',', $request->input('dateRange'));
        $extraPersonCount = $request->input('extraPersonCount');
        $discountName = $request->input('discount');
        $addons = explode(',' ,(str_replace('%', ' ', $request->input('addons'))));
        // $addOns = $request->input('addons');

        // Create a DatePeriod object
        $begin = new DateTime($filterDateRange[0]);
        $end = new DateTime($filterDateRange[1] ?? '2000-01-01');
        $interval = new DateInterval('P1D'); // Set interval to 1 day
        $dateRange = new DatePeriod($begin, $interval, $end);

        // Convert DatePeriod object to an array of dates
        $dates = [];
        foreach ($dateRange as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        $roomsQuery = Room::query();

        // Apply room type filter
        if ($filterRoomType) {
            $roomsQuery->whereHas('roomType', function ($query) use ($filterRoomType) {
                $query->where('name', $filterRoomType);
            });
        }

        // Apply room number filter
        if ($filterRoomNumber) {
            $roomsQuery->where('room_number', $filterRoomNumber);
        }

        if ($discountName === 'VOUCHER') {
            $voucherCode = Voucher::where('code', $request->voucherCode)->first();
            $discount = $voucherCode;
        } else {
            $discount = Discount::where('name', $discountName)->first();
        }

        // Set default discount value if no discount is found
        $discountValue = $discount->value ?? 0;
        
       
        $addons = array_map(function ($addon){
            $key = ['name', 'quantity'];
            $split = explode('-', $addon);
            $addon = array_combine($key, $split);
            return $addon;
        }, $addons);

        $addonsPrice = array_map(function ($addon) {
            if($addon['quantity']){
            $price = Addon::where('name', strtoupper($addon['name']))->first();
            $price = $price['price'] ?? 0;
            return $price;
            }
        }, $addons);

        $addonsTotal = array_sum(array_map(function($addon, $addonPrice){
            $total = ($addon['quantity'] * $addonPrice);
            // $sample = $total;
            return $total;
        }, $addons, $addonsPrice));

        $fullAddons = array_map(function($addon, $addonPrice){
            $total = $addon['quantity'] * $addonPrice;
            $total = (float)number_format($total, 2);
            $fullAddon = [
                'name' => $addon['name'],
                'quantity' => $addon['quantity'],
                'unit_price' => $addonPrice,
                'total' => $total,
        ];
            return $fullAddon;
        }, $addons, $addonsPrice);

        $rooms = $roomsQuery->get()->transform(function ($room) use ($filterDateRange, $dates, $extraPersonCount, $discountValue, $addonsTotal, $fullAddons) {
            $dayOfWeek = strtolower(date('l'));
            $today = date('Y-m-d');

            // Default to regular rate
            $rate = collect($room->roomType->rates)->firstWhere('type', 'REGULAR');

            // Check if there's a special rate within the date range
            $specialRate = collect($room->roomType->rates)->first(function ($rate) use ($today) {
                return $rate['type'] === 'SPECIAL' &&
                    (!$rate['start_date'] || $rate['start_date'] <= $today) &&
                    (!$rate['end_date'] || $rate['end_date'] >= $today);
            });

            // If there's a special rate, use it
            if ($specialRate) {
                $rate = $specialRate;
            }

            // Extra Person Calculation
            $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;

            return [
                'referenceNumber' => $room->reference_number,
                'roomNumber' => $room->room_number,
                'roomFloor' => $room->room_floor,
                'roomType' => $room->roomType->name ?? 'N/A',
                'roomTypeCapacity' => $room->roomType->capacity ?? 'N/A',
                'duration' => count($dates),
                'roomRateType' => $rate['type'] ?? 'N/A',
                'roomRatesArray' => array_map(function ($date) use ($rate, $room, $extraPersonCount) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
                    return [
                        'date' => $date,
                        'dayOfWeek' => $dayOfWeek,
                        'rate' => $rate[$dayOfWeek] ?? 'N/A',
                        'extraPersonRate' => $extraPersonRate * ($extraPersonCount ?? 0),
                    ];
                }, $dates),
                'addons' => $fullAddons,
                'addonsTotal' => (float)number_format($addonsTotal, 2),
                'discount' => ($discountValue * 100 . '%'), // show discount
                'roomTotal' => $addonsTotal + (array_sum(array_map(function ($date) use ($rate, $discountValue) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    return ($rate[$dayOfWeek] ?? 0) * (1 - $discountValue);
                }, $dates))),
                'extraPersonCount' => $extraPersonCount,
                'extraPersonTotal' => array_sum(array_map(function ($date) use ($rate, $room, $extraPersonCount) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
                    return $extraPersonRate * $extraPersonCount;
                }, $dates)),
                'roomTotalWithExtraPerson' =>  $addonsTotal + array_sum(array_map(function ($date) use ($rate, $room, $extraPersonCount, $discountValue) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
                    return ($rate[$dayOfWeek] + ($extraPersonRate * $extraPersonCount)) * (1 - $discountValue);
                }, $dates)),
                'extraPersonCapacity' => $room->roomType->extra_person_capacity ? range(0, $room->roomType->extra_person_capacity) : 0,
            ];
        });

        // dd($rooms);
        return response()->json([
            'message' => 'Rooms fetched successfully',
            'results' => $rooms,
            'code' => 200,
            'error' => false
        ]);
    }
}
