<?php

namespace App\Repositories\Transaction\Payment;

use App\Repositories\BaseRepository;
use App\Models\Transaction\{
    Transaction,
    Payment,
};
use App\Models\Amenity\{
    BookingAddon,
    Addon
};
use Carbon\Carbon;

class ShowPaymentRepository extends BaseRepository
{
    public function execute($request){
        $transaction = Transaction::where('reference_number', $request->referenceNumber)->first();

        if (!$transaction) {
            return $this->error('Transaction not found');
        }

        $payments = Payment::where('transaction_id', $transaction->id)
            ->orderBy('id', 'asc')
            ->get();

        $paymentIndex = 0;

        foreach ($payments as $payment) {
            if ($payment->id < $request->paymentId) {
                $paymentIndex +=1;
            }
            continue;
        }

        $payment = $payments->where('id', $request->paymentId)->first();

        if (!$payment) {
            return $this->error('Payment not found');
        }

        $fullAddons = BookingAddon::where('transaction_id', $transaction->id)
            ->where('payment_id', $payment->id)
            ->orderBy('id', 'asc')
            ->get();
        
        $transformedAddons = $fullAddons->map(function ($addon) {
            $addonModel = Addon::where('name', $addon->name)->first();
            return [
                'paymentStatus' => $addon->payment_status,
                'name' => $addon->name,
                'quantity' => $addon->quantity,
                'unitPrice' => $addonModel->price,
                'totalPrice' => $addon->total_price,
                'discount' => 0,
                'timestamp' => Carbon::parse($addon->created_at)->format('Y-m-d H:i:s'),
            ];
        });

        $discount = $transaction->voucherDiscount ?? $transaction->seniorPwdDiscount ?? null;
        if ($discount !== null) {
            $discountValue = $discount->value * $transaction->room_total;
        }

        $room[] = [];

        if ($paymentIndex > 0) {
            $room = null;
        } else {
            $baseRates = $transaction->room->roomType->rates()
                ->where('type', 'REGULAR')
                ->first();

            $date = Carbon::parse($transaction->check_in_date);
            $dayOfWeek = strtolower($date->format('l'));
            
            $room = [
                'paymentStatus' => $transaction->payment_status,
                'roomType' => $transaction->room->roomType->name,
                'quantity' => (float)$transaction->number_of_guest + 1,
                'unitPrice' => $baseRates[$dayOfWeek],
                'totalPrice' => $transaction->room_total,
                'discount' => $discountValue ?? 0,
                'timestamp' => Carbon::parse($payment->created_at)->format('Y-m-d H:i:s'),
            ];
        }
        
        return [
            'referenceNumber' => $transaction->reference_number,
            'guestName' => $transaction->guest->full_name,
            'checkIn' => $transaction->check_in_date . ' ' . $transaction->check_in_time,
            'checkOut' => $transaction->check_out_date . ' ' . $transaction->check_out_time,
            'room' => $room,
            'addons' => $transformedAddons ?? null,
            'payment' => $payment->amount_received,
            'paymentType' => $payment->payment_type,
            'totalPurchase' => number_format((float)(((($room['totalPrice'] ?? 0) - ($room['discount'] ?? 0)) + ($fullAddons->sum('total_price') ?? 0))), 2, '.', '')
        ];
    }
}
