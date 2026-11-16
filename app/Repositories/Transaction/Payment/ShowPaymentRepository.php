<?php

namespace App\Repositories\Transaction\Payment;

use App\Repositories\BaseRepository;
use App\Models\Transaction\{
    Transaction,
    Payment,
    PaymentAllocation,
};
use Carbon\Carbon;

class ShowPaymentRepository extends BaseRepository
{
    /**
     * Shows only what this single payment paid for, based on its payment allocations.
     */
    public function execute($request)
    {
        $transaction = Transaction::where('reference_number', $request->referenceNumber)->first();

        if (!$transaction) {
            return $this->error('Transaction not found');
        }

        $payment = Payment::where('transaction_id', $transaction->id)
            ->where('id', $request->paymentId)
            ->first();

        if (!$payment) {
            return $this->error('Payment not found');
        }

        $allocations = PaymentAllocation::with('bookingAddon')
            ->where('payment_id', $payment->id)
            ->orderBy('id')
            ->get();

        // one entry per charge (room or addon) this payment touched
        $byCharge = $allocations->groupBy(fn ($row) => $row->booking_addon_id ?? 'room');

        $room = null;
        $addons = collect();

        foreach ($byCharge as $key => $rows) {
            $summary = $this->summarize($rows);

            if ($key === 'room') {
                $discount = $transaction->voucherDiscount ?? $transaction->seniorPwdDiscount ?? null;
                $baseRates = $transaction->room->roomType->rates()
                    ->where('type', 'REGULAR')
                    ->first();
                $dayOfWeek = strtolower(Carbon::parse($transaction->check_in_date)->format('l'));

                $room = [
                    'paymentStatus' => $transaction->payment_status,
                    'roomType' => $transaction->room->roomType->name,
                    'quantity' => (float) $transaction->number_of_guest + 1,
                    'unitPrice' => $baseRates[$dayOfWeek] ?? null,
                    'totalPrice' => $transaction->room_total,
                    'discount' => ($discount->value ?? 0) * $transaction->room_total,
                    'timestamp' => Carbon::parse($rows->first()->created_at)->format('Y-m-d H:i:s'),
                ] + $summary;
            } else {
                $addon = $rows->first()->bookingAddon;

                $addons->push([
                    'paymentStatus' => $addon->payment_status,
                    'name' => $addon->name,
                    'quantity' => $addon->quantity,
                    // derived from the booked price so old receipts don't change if the catalog price does
                    'unitPrice' => round($addon->total_price / max($addon->quantity, 1), 2),
                    'totalPrice' => $addon->total_price,
                    'discount' => 0,
                    'timestamp' => Carbon::parse($rows->first()->created_at)->format('Y-m-d H:i:s'),
                ] + $summary);
            }
        }

        $applied = $allocations->sum('amount');
        $creditUsed = $allocations->where('type', '!=', 'REFUND')->sum('amount');

        return [
            'referenceNumber' => $transaction->reference_number,
            'guestName' => $transaction->guest->full_name,
            'checkIn' => $transaction->check_in_date . ' ' . $transaction->check_in_time,
            'checkOut' => $transaction->check_out_date . ' ' . $transaction->check_out_time,
            'room' => $room,
            'addons' => $addons->values(),
            'payment' => $payment->amount_received,
            'paymentType' => $payment->payment_type,
            // what this payment currently pays for (after any releases / refunds)
            'totalPurchase' => number_format((float) $applied, 2, '.', ''),
            // received but not applied to anything yet
            'unappliedCredit' => number_format((float) $payment->amount_received - (float) $creditUsed, 2, '.', ''),
        ];
    }

    private function summarize($rows): array
    {
        $applied = (float) $rows->where('type', 'ALLOCATION')->sum('amount');
        $released = abs((float) $rows->where('type', 'RELEASE')->sum('amount'));
        $refunded = abs((float) $rows->where('type', 'REFUND')->sum('amount'));

        return [
            'amountApplied' => number_format($applied, 2, '.', ''),
            'amountReleased' => number_format($released, 2, '.', ''),
            'amountRefunded' => number_format($refunded, 2, '.', ''),
            'netApplied' => number_format($applied - $released - $refunded, 2, '.', ''),
        ];
    }
}
