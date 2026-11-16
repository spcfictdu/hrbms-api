<?php

namespace App\Repositories\Transaction\Payment;

use App\Models\Amenity\BookingAddon;
use App\Models\Transaction\Payment;
use App\Models\Transaction\PaymentAllocation;
use App\Models\Transaction\Transaction;

/**
 * Works out which part of a transaction each payment paid for.
 *
 * Rules:
 *  - Payments are applied oldest first.
 *  - Charges are paid in order: room first, then addons by purchase batch / id.
 *  - The discount (voucher or senior/PWD) applies to the ROOM only.
 *  - Money that covers no charge stays on the payment as credit and is applied
 *    automatically the next time reconcile() runs (e.g. when an addon is added).
 *
 * Everything is calculated in integer cents to avoid float drift.
 * Call these methods inside a DB transaction.
 */
class PaymentAllocator
{
    private const CLOSED = ['VOIDED', 'REFUNDED'];

    /**
     * Apply unallocated payment credit to outstanding charges and sync
     * the PENDING / PARTIAL / PAID statuses of the room and addons.
     * Safe to call repeatedly.
     */
    public function reconcile(Transaction $transaction): void
    {
        $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

        if (in_array($transaction->payment_status, self::CLOSED, true)) {
            return;
        }

        $charges = $this->charges($transaction);
        $payments = Payment::where('transaction_id', $transaction->id)
            ->orderBy('created_at')->orderBy('id')->get();

        // 1. A discount added after payment can shrink what the room costs.
        //    Release the excess (newest payment first) so it becomes credit again.
        [$paid, $net] = $this->ledger($transaction);
        foreach ($charges as $key => $charge) {
            $over = ($paid[$key] ?? 0) - $charge['net'];

            foreach ($payments->reverse() as $payment) {
                if ($over <= 0) {
                    break;
                }
                $applied = $net[$payment->id][$key] ?? 0;
                if ($applied <= 0) {
                    continue;
                }
                $take = min($over, $applied);
                $this->record($payment, $transaction, $charge, 'RELEASE', -$take);
                $over -= $take;
            }
        }

        // 2. Walk payments oldest first and spend their remaining credit.
        [$paid, $net, $credit] = $this->ledger($transaction);
        foreach ($payments as $payment) {
            $remaining = $this->cents($payment->amount_received) - ($credit[$payment->id] ?? 0);

            foreach ($charges as $key => $charge) {
                if ($remaining <= 0) {
                    break;
                }
                $owed = $charge['net'] - ($paid[$key] ?? 0);
                if ($owed <= 0) {
                    continue;
                }
                $amount = min($remaining, $owed);
                $this->record($payment, $transaction, $charge, 'ALLOCATION', $amount);
                $paid[$key] = ($paid[$key] ?? 0) + $amount;
                $remaining -= $amount;
            }
        }

        // 3. Sync statuses.
        $roomStatus = $this->status($paid['room'] ?? 0, $charges['room']['net']);
        if ($transaction->payment_status !== $roomStatus) {
            $transaction->update(['payment_status' => $roomStatus]);
        }

        foreach ($charges as $key => $charge) {
            if ($charge['addon'] === null) {
                continue;
            }
            $status = $this->status($paid[$key] ?? 0, $charge['net']);
            if ($charge['addon']->payment_status !== $status) {
                $charge['addon']->update(['payment_status' => $status]);
            }
        }
    }

    /**
     * What the guest still owes (discounted room + non-voided addons), in pesos.
     */
    public function outstanding(Transaction $transaction): float
    {
        [$paid] = $this->ledger($transaction);

        $due = 0;
        foreach ($this->charges($transaction) as $key => $charge) {
            $due += max($charge['net'] - ($paid[$key] ?? 0), 0);
        }

        return $due / 100;
    }

    /**
     * Amount currently allocated to the room (null) or to one addon, in pesos.
     */
    public function allocated(Transaction $transaction, ?BookingAddon $addon = null): float
    {
        [$paid] = $this->ledger($transaction);

        return ($paid[$addon?->id ?? 'room'] ?? 0) / 100;
    }

    /**
     * Undo every allocation on the room (null) or on one addon.
     *
     * $type RELEASE: the money stays with the guest as credit (void).
     * $type REFUND:  the money is handed back (refund).
     *
     * Returns the amount reversed, in pesos. Appends negative rows, never edits old ones.
     */
    public function reverse(Transaction $transaction, ?BookingAddon $addon, string $type): float
    {
        $rows = PaymentAllocation::where('transaction_id', $transaction->id)
            ->when(
                $addon,
                fn ($q) => $q->where('booking_addon_id', $addon->id),
                fn ($q) => $q->whereNull('booking_addon_id')
            )
            ->get();

        $item = $addon ? 'ADDON' : 'ROOM';
        $reversed = 0;

        foreach ($rows->groupBy('payment_id') as $paymentId => $group) {
            $net = $this->cents($group->sum('amount'));
            if ($net <= 0) {
                continue;
            }

            PaymentAllocation::create([
                'payment_id'       => $paymentId,
                'transaction_id'   => $transaction->id,
                'booking_addon_id' => $addon?->id,
                'item'             => $item,
                'type'             => $type,
                'amount'           => -$net / 100,
            ]);
            $reversed += $net;
        }

        return $reversed / 100;
    }

    /** Charges in payment order, keyed 'room' / addon id, with the net amount in cents. */
    private function charges(Transaction $transaction): array
    {
        // Discount applies to the room only.
        $discount = (float) ($transaction->voucherDiscount?->value
            ?? $transaction->seniorPwdDiscount?->value
            ?? 0);

        $charges = [
            'room' => [
                'addon' => null,
                'item'  => 'ROOM',
                'net'   => $this->cents($transaction->room_total * (1 - $discount)),
            ],
        ];

        $addons = BookingAddon::where('transaction_id', $transaction->id)
            ->whereNotIn('payment_status', self::CLOSED)
            ->orderBy('purchase_batch')->orderBy('id')->get();

        foreach ($addons as $addon) {
            $charges[$addon->id] = [
                'addon' => $addon,
                'item'  => 'ADDON',
                'net'   => $this->cents($addon->total_price),
            ];
        }

        return $charges;
    }

    /**
     * Reads the allocation rows and returns, in cents:
     *  [0] paid   per charge key
     *  [1] net    per payment id, per charge key
     *  [2] credit used per payment id (allocations minus releases; refunds stay spent)
     */
    private function ledger(Transaction $transaction): array
    {
        $paid = $net = $used = [];

        foreach (PaymentAllocation::where('transaction_id', $transaction->id)->get() as $row) {
            $key = $row->booking_addon_id ?? 'room';
            $cents = $this->cents($row->amount);

            $paid[$key] = ($paid[$key] ?? 0) + $cents;
            $net[$row->payment_id][$key] = ($net[$row->payment_id][$key] ?? 0) + $cents;

            if ($row->type !== 'REFUND') {
                $used[$row->payment_id] = ($used[$row->payment_id] ?? 0) + $cents;
            }
        }

        return [$paid, $net, $used];
    }

    private function record(Payment $payment, Transaction $transaction, array $charge, string $type, int $cents): void
    {
        PaymentAllocation::create([
            'payment_id'       => $payment->id,
            'transaction_id'   => $transaction->id,
            'booking_addon_id' => $charge['addon']?->id,
            'item'             => $charge['item'],
            'type'             => $type,
            'amount'           => $cents / 100,
        ]);
    }

    private function status(int $paid, int $net): string
    {
        if ($paid >= $net) {
            return 'PAID';
        }

        return $paid > 0 ? 'PARTIAL' : 'PENDING';
    }

    private function cents($value): int
    {
        return (int) round(((float) $value) * 100);
    }
}
