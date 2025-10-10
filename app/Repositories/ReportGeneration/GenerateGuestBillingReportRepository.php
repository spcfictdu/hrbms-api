<?php

namespace App\Repositories\ReportGeneration;

use App\Repositories\BaseRepository;
use App\Models\Transaction\Transaction;
use App\Models\Amenity\BookingAddon;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Folio;
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
            if ($discount !== null) {
                $discountValue = (float)$transaction->room_total * ((float)$discount->value);
            } else {
                $discountValue = 0;
            }

            $payments = Payment::where('transaction_id', $transaction->id)
                ->get();

            $fullAddons = BookingAddon::with('payment')
                ->where('transaction_id', $transaction->id)
                ->get();

            // $addonsCharges = $chargeDistribution->where('item', 'ADDON')
            //     ->get();
            // $transformedAddonsCharges = $addonsCharges->map(function ($charge) {
            //     return [
                    
            //     ];
            // });

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
                    'folio' => [
                        'folioType' => $addon->folio->type,
                        'folioA' => [
                            'name' => $addon->folio->folio_a_name,
                            'charge' => $addon->folio->folio_a_charge * $addon->total_price,
                        ] ?? null,
                        'folioB' => [
                            'name' => $addon->folio->folio_b_name,
                            'charge' => $addon->folio->folio_b_charge * $addon->total_price,
                        ] ?? null,
                        'folioC' => [
                            'name' => $addon->folio->folio_c_name,
                            'charge' => $addon->folio->folio_c_charge * $addon->total_price,
                        ] ?? null,
                        'folioD' => [
                            'name' => $addon->folio->folio_d_name,
                            'charge' => $addon->folio->folio_d_charge * $addon->total_price,
                        ] ?? null,
                    ],
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

            $chargeDistribution = Folio::where('transaction_id', $transaction->id)
                ->get();
            $roomCharges = $chargeDistribution->where('item', 'ROOM')
                ->first();
            $transformedRoomCharges = [
                'folioType' => $roomCharges->type,
                'folioA' => [
                    'name' => $roomCharges->folio_a_name,
                    'charge' => $roomCharges->folio_a_charge * ($transaction->room_total - $discountValue)
                ],
                'folioB' => [
                    'name' => $roomCharges->folio_b_name,
                    'charge' => $roomCharges->folio_b_charge * ($transaction->room_total - $discountValue)
                ] ?? null,
                'folioC' => [
                    'name' => $roomCharges->folio_c_name,
                    'charge' => $roomCharges->folio_c_charge * ($transaction->room_total - $discountValue)
                ] ?? null,
                'folioD' => [
                    'name' => $roomCharges->folio_d_name,
                    'charge' => $roomCharges->folio_d_charge * ($transaction->room_total - $discountValue)
                ] ?? null,
            ];

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
                'roomCharges' => $transformedRoomCharges,
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
