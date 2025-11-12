<?php

namespace App\Repositories\Enum;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\Room\Room;
use App\Models\Amenity\Addon;
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
        $addonsInput = $request->input('addons', ''); // Default to empty string if not provided
        $addons = $addonsInput ? explode(',', str_replace('%', ' ', $addonsInput)) : [];

        // Create a DatePeriod object
        $begin = new DateTime($filterDateRange[0]);
        $end = new DateTime($filterDateRange[1] ?? $filterDateRange[0]); // fallback to same day if missing
        $interval = new DateInterval('P1D');

        // If same-day checkout or invalid range, include at least one date (the check-in date)
        if ($begin == $end) {
            $dates = [$begin->format('Y-m-d')];
        } else {
            $dateRange = new DatePeriod($begin, $interval, $end);
            $dates = [];
            foreach ($dateRange as $date) {
                $dates[] = $date->format('Y-m-d');
            }
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

        $addons = array_map(function ($addon) {
            $key = ['name', 'quantity'];
            $split = explode('-', $addon);
            $addon = array_combine($key, $split);
            return $addon;
        }, $addons);

        $addonsPrice = array_map(function ($addon) {
            if ($addon['quantity']) {
                $price = Addon::where('name', strtoupper($addon['name']))->first();
                $price = $price['price'] ?? 0;
                return $price;
            }
        }, $addons);

        $addonsTotal = array_sum(array_map(function ($addon, $addonPrice) {
            $total = ($addon['quantity'] * $addonPrice);
            return $total;
        }, $addons, $addonsPrice));
        $addonsTotal =  round($addonsTotal, 2);

        $fullAddons = array_map(function ($addon, $addonPrice) {
            $total = $addon['quantity'] * $addonPrice;
            $total = round($total, 2);
            $fullAddon = [
                'name' => $addon['name'],
                'quantity' => $addon['quantity'],
                'unitPrice' => $addonPrice,
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
                'addonsTotal' => $addonsTotal,
                'discount' => ($discountValue * 100 . '%'), // show discount
                'roomTotal' => array_sum(array_map(function ($date) use ($rate, $discountValue) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    return ($rate[$dayOfWeek] ?? 0);
                }, $dates)),
                'extraPersonCount' => $extraPersonCount,
                'extraPersonTotal' => array_sum(array_map(function ($date) use ($rate, $room, $extraPersonCount, $discountValue) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
                    return $extraPersonRate * $extraPersonCount;
                }, $dates)),
                'roomTotalWithExtraPerson' =>  array_sum(array_map(function ($date) use ($rate, $room, $extraPersonCount, $discountValue) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
                    return ($rate[$dayOfWeek] + ($extraPersonRate * $extraPersonCount));
                }, $dates)),
                'discountedAmount' => round($addonsTotal * $discountValue + array_sum(array_map(function ($date) use ($rate, $room, $extraPersonCount, $discountValue, $addonsTotal) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
                    return ($rate[$dayOfWeek] + ($extraPersonRate * $extraPersonCount)) * $discountValue;
                }, $dates)), 2),
                'extraPersonCapacity' => $room->roomType->extra_person_capacity ? range(0, $room->roomType->extra_person_capacity) : 0,
            ];
        });

        return response()->json([
            'message' => 'Rooms fetched successfully',
            'results' => $rooms,
            'code' => 200,
            'error' => false
        ]);
    }
}
