<?php

namespace App\Repositories\Transaction;

use App\Models\Amenity\BookingAddOn;
use App\Models\Transaction\{
    Transaction,
    Payment
};
use App\Models\amenity\Addon;
use Carbon\Carbon;
use Illuminate\Support\{Str, Arr};
use App\Repositories\BaseRepository;

class ShowTransactionRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $transaction = Transaction::where('reference_number', $referenceNumber)->firstOrFail();

        $transactionHistory = $transaction->transactionHistory ?? null;
        $room = $transaction->room ?? null;
        $roomType = $room->roomType ?? null;
        $roomTypeRate = $roomType->rates->first();
        $guest = $transaction->guest;
        $payment = $transaction->payment;
        $day = Str::lower($transaction->created_at->format('l'));
        $roomTotal = $transaction->room_total;

        // Parse the check-in and check-out dates
        $checkInDate = Carbon::createFromFormat('Y-m-d', $transaction->check_in_date);
        $checkOutDate = Carbon::createFromFormat('Y-m-d', $transaction->check_out_date);

        // Initialize the total cost
        $totalCost = 0;
        $diffInDays = $checkInDate->diffInDays($checkOutDate);

        // Loop through each day between the check-in and check-out dates
        for ($date = $checkInDate; $date->lte($checkOutDate); $date->addDay()) {
            // Get the day of the week (lowercase to match the property name)
            $dayOfWeek = strtolower($date->format('l')); // 'monday', 'tuesday', etc.

            // Retrieve the price for the specific day from the RoomTypeRate model
            // $priceForDay = $roomTypeRate->$dayOfWeek;

            // Add the price for the day to the total cost
            // $totalCost += $priceForDay;
        }

        // Calculate add-ons total
        $addons = $transaction->bookingAddon ?? [];
        $addonsTotal = 0;
        $fullAddons = [];
        foreach ($addons as $addon) {
            $addonModel = Addon::where('name', $addon['name'])->first();
            if ($addonModel) {
                $totalPrice = $addonModel->price * $addon['quantity'];
                $addonsTotal += $totalPrice;
                $fullAddons[] = [
                    'name' => $addon['name'],
                    'quantity' => $addon['quantity'],
                    'unit_price' => $addonModel->price,
                    // 'total' => $totalPrice,
                    'total' => (float)number_format($totalPrice, 2),
                ];
            }
        }

        // Calculate discount
        $discountValue = 0;

        if ($transaction->payment->voucherDiscount) {
            $discountValue = $transaction->payment->voucherDiscount->value ?? 0;
        } else {
            $discountValue = $transaction->payment->seniorPwdDiscount->value ?? 0;
        }

        // Calculate room total with discount and add-ons
        $roomTotal = $addonsTotal + ($totalCost * (1 - $discountValue));

        $fullAddons = BookingAddOn::where('transaction_id', $transaction->id)->get() ?? null;

        // Calculate discount
        $discountValue = 0;

        if ($transaction->payment->voucherDiscount) {
            $discountValue = $transaction->payment->voucherDiscount->value ?? 0;
        } else {
            $discountValue = $transaction->payment->seniorPwdDiscount->value ?? 0;
        }

        return $this->success("Transaction Info", [
            "bookingHistory" => [
                "room" => [
                    "number" => $room->room_number,
                    "name" => $roomType->name,
                    "capacity" => $roomType->capacity,
                ],
                "transaction" => [
                    "referenceNumber" => $transaction->reference_number,
                    "status" => $transaction->status,
                    "extraPerson" => $transaction->number_of_guest,
                    "checkInDate" => $transaction->check_in_date,
                    "checkInTime" => $transaction->check_in_time,
                    "checkOutDate" => $transaction->check_out_date,
                    "checkOutTime" => $transaction->check_out_time,
                ],
                "transactionHistory" => [
                    "checkInDate" => $transactionHistory?->check_in_date,
                    "checkInTime" => $transactionHistory?->check_in_time,
                    "checkOutDate" => $transactionHistory?->check_out_date,
                    "checkOutTime" => $transactionHistory?->check_out_time,
                ],
                "guestName" => $guest->full_name,
                "priceSummary" => [
                    "days" => $diffInDays,
                    "fullAddons" => $fullAddons,
                    "discount" => ($discountValue*100 . '%'),
                    "roomTotal" => $roomTotal,
                ],
                "paymentSummary" => [
                    "paymentType" => $payment->payment_type ?? null,
                    "amountReceived" => $payment->amount_received ?? null
                ]
            ]
        ]);
    }
}
