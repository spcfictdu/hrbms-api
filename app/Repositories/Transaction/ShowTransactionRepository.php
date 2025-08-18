<?php

namespace App\Repositories\Transaction;

use App\Models\Amenity\BookingAddOn;
use App\Models\Transaction\{
    Transaction,
    Payment,
};
use App\Models\Discount\Voucher;
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
        // $payment = $transaction->payment;
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
                    'addonId' => $addon['id'],
                    'quantity' => $addon['quantity'],
                    'unitPrice' => $addonModel->price,
                    // 'total' => $totalPrice,
                    'total' => (float)number_format($totalPrice, 2),
                    'paymentStatus' => $addon->payment_status,
                    'createdAt' => $addon->created_at,
                ];
            }
        }


        // $fullAddons = BookingAddOn::where('transaction_id', $transaction->id)->get() ?? null;

        //show multiple payments in a single transaction
        $payments = Payment::where('transaction_id', $transaction->id)
            ->get();
            
        $paymentSummary = [];
        $discountValue = 0;
        $discountName = NULL;

        foreach ($payments as $payment) {
            // Calculate discount
        
            if (isset($payment->voucherDiscount)) {
                $discountValue = $payment->voucherDiscount->value ?? 0;
                $discountName = 'VOUCHER';
                $discountCode = Voucher::where('id', $payment->voucherDiscount->voucher_id)->first()->code;
                
                // dd($discountCode);
            } elseif(isset($payment->seniorPwdDiscount)) {
                $discountValue = $payment->seniorPwdDiscount->value ?? 0;
                $discountName = $payment->seniorPwdDiscount->discount;
                $snrPwdId = $payment->seniorPwdDiscount->id_number;
            }


            $paymentSummary[] = [
                    'paymentType' => $payment->payment_type,
                    'amountReceived' => $payment->amount_received,
            ];
        }

        // Calculate room total with discount and add-ons
        $finalRoomTotal =$roomTotal * (1 - $discountValue);
        
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
                    "paymentStatus" => $transaction->payment_status,
                    "extraPerson" => $transaction->number_of_guest,
                    "checkInDate" => $transaction->check_in_date,
                    "checkInTime" => $transaction->check_in_time,
                    "checkOutDate" => $transaction->check_out_date,
                    "checkOutTime" => $transaction->check_out_time,
                    "createdAt" => $transaction->created_at,
                ],
                "transactionHistory" => [
                    "checkInDate" => $transactionHistory?->check_in_date,
                    "checkInTime" => $transactionHistory?->check_in_time,
                    "checkOutDate" => $transactionHistory?->check_out_date,
                    "checkOutTime" => $transactionHistory?->check_out_time,
                ],
                "guestName" => $guest->full_name,
                "guestId" => $guest->id,
                "priceSummary" => [
                    "days" => $diffInDays,
                    "fullAddons" => $fullAddons,
                    "discount" => ($discountValue*100 . '%'),
                    "voucherCode" => $discountCode ?? null,
                    "idNumber" => $snrPwdId ?? null,
                    "discountName" => $discountName,
                    "roomTotal" => $roomTotal,
                    "finalRoomTotal" => $finalRoomTotal,
                ],
                "paymentSummary" => $paymentSummary
                // [
                //     "paymentType" => $payment->payment_type ?? null,
                //     "amountReceived" => $payment->amount_received ?? null
                // ]
            ]
        ]);
    }
}
