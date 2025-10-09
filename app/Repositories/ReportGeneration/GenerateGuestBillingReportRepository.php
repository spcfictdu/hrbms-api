<?php

namespace App\Repositories\ReportGeneration;

use App\Repositories\BaseRepository;
use App\Models\Transaction\Transaction;
use App\Models\Amenity\BookingAddon;
use App\Models\Transaction\Payment;
use Carbon\Carbon;

class GenerateGuestBillingReportRepository extends BaseRepository
{
    public function execute($request, $referenceNumber){
        $transaction = Transaction::where('reference_number', $referenceNumber)->first();

        if (!$transaction) {
            return $this->error('Transaction not found');
        }

        $guest = $transaction->guest;

        if ($guest) {
            $discount = $transaction->voucherDiscount ?? $transaction->seniorPwdDiscount ?? null;
            if ($discount) {
                $discountValue = (float)$transaction->room_total * (float)$discount->value;
            }

            $payments = Payment::where('transaction_id', $transaction->id)
                ->get();

            $fullAddons = BookingAddon::with('payment')
                ->where('transaction_id', $transaction->id)
                ->get();

            $addonsTotal = $fullAddons->whereNotIn('payment_status', ['VOIDED', 'REFUNDED'])
                ->sum('total_price') ?? 0;

            $grossTotal = $transaction->room_total + $addonsTotal;

            $grandTotal = ((float)$transaction->room_total - (float)$discountValue) + (float)$addonsTotal;

            $transformedAddons = $fullAddons->map(function ($addon) {
                return [
                    'item' => $addon->name,
                    'quantity' => $addon->quantity,
                    'price' => number_format((float) $addon->total_price, 2, '.', ''),
                    'paymentId' => $addon->payment_id ?? $addon->payment?->id,
                    'paymentType' => $addon->payment->payment_type,
                    'paymentAmount' => number_format((float) ($addon->payment->amount_received ?? 0), 2, '.', ''),
                    'paymentStatus' => $addon->payment_status,
                    'user' => $addon->payment->user->username,
                    'datetime' => Carbon::parse($addon->created_at)->format('Y-m-d H:i:s'),
                ];
            });

            $transformedPayments = $payments->map(function ($payment) {
                return [
                    'item' => 'PAYMENT',
                    'quantity' => 1,
                    'price' => 0,
                    'paymentId' => $payment->id,
                    'paymentType' => $payment->payment_type,
                    'paymentAmount' => number_format((float) ($payment->amount_received ?? 0), 2, '.', ''),
                    'paymentStatus' => null,
                    'user' => $payment->user->username,
                    'datetime' => Carbon::parse($payment->created_at)->format('Y-m-d H:i:s'),
                ];
            });

            $mergedTransactions = $transformedAddons->merge($transformedPayments);

            $mergedTransactions = $mergedTransactions->sortBy(function ($item) {
                return Carbon::parse($item['datetime'])->timestamp;
            })->values();

            $totalBalance = $transaction->room_total - $discountValue;

            $mergedTransactions = $mergedTransactions->map(function ($item) use (&$totalBalance) {
                if ($item['item'] === 'PAYMENT') {
                    $totalBalance -= (float) $item['paymentAmount'];
                } else {
                    if (!in_array($item['paymentStatus'], ['VOIDED', 'REFUNDED'])) {
                        $totalBalance += (float) $item['price'];
                    }
                }

                $item['balance'] = number_format((float) $totalBalance, 2, '.', '');
                return $item;
            });

            $orderedTransactions = $mergedTransactions->values();

            if ($transaction->transactionHistory) {
                $checkIn = $transaction->transactionHistory->check_in_date && $transaction->transactionHistory->check_in_time
                    ? $transaction->transactionHistory->check_in_date . ' ' . $transaction->transactionHistory->check_in_time
                    : null;

                $checkOut = $transaction->transactionHistory->check_out_date && $transaction->transactionHistory->check_out_time
                    ? $transaction->transactionHistory->check_out_date . ' ' . $transaction->transactionHistory->check_out_time
                    : null;
            } else {
                $checkIn = null;
                $checkOut = null;
            }

            return [
                'guestName' => $guest->full_name,
                'referenceNumber' => $referenceNumber,
                'roomNumber' => $transaction->room->room_number,
                'roomType' => $transaction->room->roomType->name,
                'totalGuests' => $transaction->number_of_guest + 1,
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
                'roomTotal' => number_format((float) $transaction->room_total, 2, '.', ''),
                'addonsTotal' => number_format((float) $addonsTotal, 2, '.', ''),
                'grossTotal' => number_format((float) $grossTotal, 2, '.', ''),
                'discount' => $discount->discount ?? null,
                'discountedAmount' => number_format((float) $discountValue, 2, '.', ''),
                'grandTotal' => number_format((float) $grandTotal, 2, '.', ''),
                'transactions' => $orderedTransactions
            ];
        }
    }
}
