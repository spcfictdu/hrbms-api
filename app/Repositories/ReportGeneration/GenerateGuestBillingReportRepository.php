<?php

namespace App\Repositories\ReportGeneration;

use App\Repositories\BaseRepository;
use App\Models\Transaction\Transaction;
use App\Models\Amenity\BookingAddon;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Folio;
use App\Models\Room\RoomType;
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
            $payments = Payment::where('transaction_id', $transaction->id)
                ->get();

            $fullAddons = BookingAddon::with('payment')
                ->where('transaction_id', $transaction->id)
                ->get();
            
            $discount = $transaction->voucherDiscount ?? $transaction->seniorPwdDiscount ?? null;
            if ($discount !== null) {
                $discountValue = ((float)$transaction->room_total + (float)$fullAddons->whereNotIn('payment_status', ['VOIDED', 'REFUNDED'])->sum('total_price')) * ((float)$discount->value);
            } else {
                $discountValue = 0;
            }

            // $addonsCharges = $chargeDistribution->where('item', 'ADDON')
            //     ->get();
            // $transformedAddonsCharges = $addonsCharges->map(function ($charge) {
            //     return [
                    
            //     ];
            // });

            $addonsTotal = $fullAddons->whereNotIn('payment_status', ['VOIDED', 'REFUNDED'])
                ->sum('total_price') ?? 0;

            $grossTotal = $transaction->room_total + $addonsTotal;

            $grandTotal = ((float)$transaction->room_total  + (float)$addonsTotal) - (float)$discountValue;

            $transformedAddons = $fullAddons->map(function ($addon) {
                if ($addon->folio->folio_a_amount <= 0) {
                    $aCharge = $addon->folio->folio_a_charge * $addon->total_price;
                } else {
                    $aCharge = $addon->folio->folio_a_amount;
                }
                if ($addon->folio->folio_b_amount <= 0) {
                    $bCharge = $addon->folio->folio_b_charge * $addon->total_price;
                } else {
                    $bCharge = $addon->folio->folio_b_amount;
                }
                if ($addon->folio->folio_c_amount <= 0) {
                    $cCharge = $addon->folio->folio_c_charge * $addon->total_price;
                } else {
                    $cCharge = $addon->folio->folio_c_amount;
                }
                if ($addon->folio->folio_d_amount <= 0) {
                    $dCharge = $addon->folio->folio_d_charge * $addon->total_price;
                } else {
                    $dCharge = $addon->folio->folio_d_amount;
                }
                return [
                    'item' => $addon->name,
                    'quantity' => $addon->quantity,
                    'price' => number_format((float) $addon->total_price, 2, '.', ''),
                    'paymentId' => $addon->payment_id ?? $addon->payment?->id,
                    'paymentType' => null,
                    'paymentAmount' => 0,
                    'paymentStatus' => $addon->payment_status,
                    'folio' => [
                        'folioType' => $addon->folio->type,
                        'folioA' => [
                            'name' => $addon->folio->folio_a_name,
                            'charge' => $aCharge,
                        ] ?? null,
                        'folioB' => [
                            'name' => $addon->folio->folio_b_name,
                            'charge' => $bCharge,
                        ] ?? null,
                        'folioC' => [
                            'name' => $addon->folio->folio_c_name,
                            'charge' => $cCharge,
                        ] ?? null,
                        'folioD' => [
                            'name' => $addon->folio->folio_d_name,
                            'charge' => $dCharge,
                        ] ?? null,
                    ],
                    'user' => $addon->payment->user->username ?? null,
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
                    'user' => $payment->user->username ?? null,
                    'datetime' => Carbon::parse($payment->created_at)->format('Y-m-d H:i:s'),
                ];
            });

            $roomPayment = Payment::where('transaction_id', $transaction->id)
                ->orderBy('id', 'asc')
                ->first();

            $chargeDistribution = Folio::where('transaction_id', $transaction->id)
                ->get();
            $roomCharges = $chargeDistribution->where('item', 'ROOM')
                ->first();

            if ($roomCharges->folio_a_amount <= 0) {
                $aCharge = $roomCharges->folio_a_charge * ($transaction->room_total - $discountValue);
            } else {
                $aCharge = $roomCharges->folio_a_amount;
            }
            if ($roomCharges->folio_b_amount <= 0) {
                $bCharge = $roomCharges->folio_b_charge * ($transaction->room_total - $discountValue);
            } else {
                $bCharge = $roomCharges->folio_b_amount;
            }
            if ($roomCharges->folio_c_amount <= 0) {
                $cCharge = $roomCharges->folio_c_charge * ($transaction->room_total - $discountValue);
            } else {
                $cCharge = $roomCharges->folio_c_amount;
            }
            if ($roomCharges->folio_d_amount <= 0) {
                $dCharge = $roomCharges->folio_d_charge * ($transaction->room_total - $discountValue);
            } else {
                $dCharge = $roomCharges->folio_d_amount;
            }
            $transformedRoomCharges = [
                'folioType' => $roomCharges->type,
                'folioA' => [
                    'name' => $roomCharges->folio_a_name,
                    'charge' => $aCharge
                ],
                'folioB' => [
                    'name' => $roomCharges->folio_b_name,
                    'charge' => $bCharge
                ] ?? null,
                'folioC' => [
                    'name' => $roomCharges->folio_c_name,
                    'charge' => $cCharge
                ] ?? null,
                'folioD' => [
                    'name' => $roomCharges->folio_d_name,
                    'charge' => $dCharge
                ] ?? null,
            ];

            $room = [
                'item' => $transaction->room->roomType->name,
                'quantity' => (int)$transaction->number_of_guest + 1,
                'price' => $transaction->room_total,
                'paymentId' => $roomPayment->id,
                'paymentType' => null,
                'paymentAmount' => 0,
                'paymentStatus' => $transaction->payment_status,
                'folio' => $transformedRoomCharges,
                'user' => $roomPayment->user->username ?? null,
                'datetime' => Carbon::parse($transaction->created_at)->format('Y-m-d H:i:s'),
            ];

            $room = collect([$room]);
            $transformedAddons = collect($transformedAddons);
            $transformedPayments = collect($transformedPayments);

            $mergedItems = $room->merge($transformedAddons);
            $mergedTransactions = $mergedItems->merge($transformedPayments);

            $mergedTransactions = $mergedTransactions->sortBy(function ($item) {
                return Carbon::parse($item['datetime'])->timestamp;
            })->values();

            $totalBalance = $transaction->room_total - $discountValue;

            $mergedTransactions = $mergedTransactions->map(function ($item) use (&$totalBalance) {
                $roomTypeNames = RoomType::pluck('name')->toArray();
                
                if ($item['item'] === 'PAYMENT') {
                    $totalBalance -= (float) $item['paymentAmount'];
                } elseif (in_array($item['item'], $roomTypeNames)) {
                    $totalBalance = $totalBalance;
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
