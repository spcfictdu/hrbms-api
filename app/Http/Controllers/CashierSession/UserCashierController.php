<?php

namespace App\Http\Controllers\CashierSession;

use App\Http\Controllers\Controller;
use App\Models\CashierSession\CashierSession;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\Payment;
use App\Models\Amenity\BookingAddon;
use App\Models\User;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class UserCashierController extends Controller
{
    use ResponseAPI;
    public function startSession(Request $request, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return $this->error('User not found.');
        }

        if (!$user->hasRole('FRONT DESK')) {
            return $this->error('User is not front desk');
        } elseif (!auth()->user()->hasRole('ADMIN') && (int) auth()->user()->id !== (int) $user->id) {
            return $this->error('Forbidden Access');
        }

        // Check if user has an opened cashier,
        // If there is, they cannot create another

        // if ($user->cashierSessions)
        $activeSession = $user->cashierSessions->where('status', 'ACTIVE')->first();
        if ($activeSession) {
            return $this->error('User already has an active cashier');
        }

        $userLatestCashierSession = $user->cashierSessions()->latest()->first();
        $latestClosingBalance = $userLatestCashierSession->closing_balance ?? 0;
        $latestClosingAdjustment = $userLatestCashierSession->closing_adjustment ?? 0;
        $latestClosing = $latestClosingBalance + $latestClosingAdjustment;

        $request->validate([
            'openingAdjustment' => 'required|integer|min:0'
        ]);

        $cashierSession = CashierSession::create([
            'user_id' => $user->id,
            'opening_balance' => $latestClosing,
            'opening_adjustment' => $request->openingAdjustment,
            'beginning_balance' => $latestClosing + $request->openingAdjustment,
            'opened_at' => Date::now(),
            'status' => 'ACTIVE'
        ]);

        return $this->success('Success', $cashierSession);
    }

    public function closeSession(Request $request, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return $this->error('User not found.');
        }

        $request->validate([
            'closingBalance' => 'required|numeric|min:0',
            'closingAdjustment' => 'required|numeric',
        ]);

        $userActiveCashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();

        // Calculate the total payments received
        $totalPayments = $userActiveCashierSession->payments->sum('amount_received');

        $beginningBalance = $userActiveCashierSession->beginning_balance;

        $total = $totalPayments + $beginningBalance;

        // Check if the total matches the closing balance
        if ($total !== (float) $request->closingBalance) {
            return $this->error('Total and closing balance are not equal', 500, $total,);
        }

        // Check if new drawer cash total would become negative
        if (($request->closingBalance + $request->closingAdjustment) < 0) {
            return $this->error('New drawer cash total is less than 0');
        }

        $userActiveCashierSession->update([
            'closing_balance' => $request->closingBalance,
            'closing_adjustment' => $request->closingAdjustment,
            'closed_at' => Date::now(),
            'status' => 'INACTIVE'
        ]);

        return $this->success('The user\'s cashier has been closed.');
    }

    public function showCashiers()
    {
        if (auth()->user()->hasRole('ADMIN')) {
            $users = User::Role('FRONT DESK')->get();

            $data = [];

            foreach($users as $user) {

                $userLatestCashierSession = $user->cashierSessions()->latest()->first();
                if (!$userLatestCashierSession) {
                    $data[] = [
                        'userId' => $user->id,
                        'message' => 'User has no cashier sessions',
                    ];
                    continue;
                }

                // data = { drawerCash: $$$, payments: [{name: gcash, amount: $$$}, {name: cash, amount: $$$}...]}
                // $allPaymentTypes = DB::table('payment_methods')->pluck('name')->toArray();

                $allPaymentTypes = ['CASH', 'GCASH', 'CHEQUE', 'CREDIT_CARD'];

                $payments = $userLatestCashierSession->payments->groupBy('payment_type')->map(function ($payments, $types) {
                    return [
                        'name' => $types,
                        'totalAmount' => number_format((float) $payments->sum('amount_received'), 2, '.', '')
                        // 'totalAmount' => 
                    ];
                })->values();

                // Add 0 amount for payment types that are not present
                foreach ($allPaymentTypes as $type) {
                    if (!$payments->contains('name', $type)) {
                        $payments->push([
                            'name' => $type,
                            'totalAmount' => '0.00'
                        ]);
                    }
                }

                $data[] = [
                    'userId' => $user->id,
                    'fullName' => $user->full_name,
                    'status' => $userLatestCashierSession->status,
                    'openingBalance' => $userLatestCashierSession->opening_balance,
                    'openingAdjustment' => $userLatestCashierSession->opening_adjustment,
                    'beginningBalance' => $userLatestCashierSession->beginning_balance,
                    'closingAdjustment' => $userLatestCashierSession->closing_adjustment,
                    'closingBalance' => $userLatestCashierSession->closing_balance,
                    'payments' => $payments,
                ];
            }
        } elseif (auth()->user()->hasRole('FRONT DESK')) {
            $user = auth()->user();

            $data = [];

            $userLatestCashierSession = $user->cashierSessions()->latest()->first();
            if (!$userLatestCashierSession) {
                $data[] = [
                    'userId' => $user->id,
                    'message' => 'User has no cashier sessions',
                ];
            }

            $allPaymentTypes = ['CASH', 'GCASH', 'CHEQUE', 'CREDIT_CARD'];

            $payments = $userLatestCashierSession->payments->groupBy('payment_type')->map(function ($payments, $types) {
                return [
                    'name' => $types,
                    'totalAmount' => number_format((float) $payments->sum('amount_received'), 2, '.', '')
                ];
            })->values();

            foreach ($allPaymentTypes as $type) {
                if (!$payments->contains('name', $type)) {
                    $payments->push([
                        'name' => $type,
                        'totalAmount' => '0.00'
                    ]);
                }
            }

            $data[] = [
                'userId' => $user->id,
                'fullName' => $user->full_name,
                'status' => $userLatestCashierSession->status,
                'openingBalance' => $userLatestCashierSession->opening_balance,
                'openingAdjustment' => $userLatestCashierSession->opening_adjustment,
                'beginningBalance' => $userLatestCashierSession->beginning_balance,
                'closingAdjustment' => $userLatestCashierSession->closing_adjustment,
                'closingBalance' => $userLatestCashierSession->closing_balance,
                'payments' => $payments,
            ];
        }

        return $this->success('Success, cashier data has been fetched', $data);
    }

    public function showHistory(Request $request, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return $this->error('User not found.');
        }

        $sessions = CashierSession::with([
            'payments.transaction.guest',
            'user'
        ])->where('user_id', $userId)
        ->orderBy('opened_at', 'desc')
        ->paginate(2);

        if ($sessions->isEmpty()) {
            return $this->success('No cashier history for user.', []);
        }

        $historyData = $sessions->map(function ($session) {
            $payments = $session->payments->map(function ($payment) {
                $transaction = $payment->transaction;
                $roomTotal = $transaction?->room_total ?? 0;
                $discountRate = $payment->voucherDiscount->value ?? $payment->seniorPwdDiscount->value ?? 0;
                $discount = $roomTotal * $discountRate;
                $roomRefund = Transaction::where('id', $transaction->id)
                    ->where('payment_status', 'REFUNDED')
                    ->first();
                $addonRefund = BookingAddon::where('transaction_id', $transaction->id)
                    ->where('payment_status', 'REFUNDED')
                    ->sum('total_price');
                $totalRefund = ($roomRefund->room_total ?? 0) + ($addonRefund ?? 0);
                $roomVoided = Transaction::where('id', $transaction->id)
                    ->where('payment_status', 'VOIDED')
                    ->first();
                $addonVoided = BookingAddon::where('transaction_id', $transaction->id)
                    ->where('payment_status', 'VOIDED')
                    ->sum('total_price');
                $totalVoided = ($roomVoided->room_total ?? 0) + ($addonVoided ?? 0);
                $fullAddons = BookingAddon::where('transaction_id', $transaction->id)
                    ->sum('total_price');
                $totalPayments = Payment::where('transaction_id', $transaction->id)
                    ->sum('amount_received');

                return [
                    'referenceNumber' => $transaction->reference_number,
                    'paymentId' => $payment->id,
                    'guestName' => optional($transaction?->guest)->full_name,
                    'paymentType' => $payment->payment_type,
                    'amountReceived' => number_format((float) $payment->amount_received, 2, '.', ''),
                    'roomTotal' => number_format((float) $roomTotal, 2, '.', ''),
                    'addOnTotal' => number_format((float) $transaction?->bookingAddon->sum('total_price'), 2, '.', '') ?? 0,
                    'discount' => number_format((float) $discount, 2, '.', ''),
                    'createdAt' => $payment->created_at,
                    'total' => number_format((float) ($transaction->room_total + $fullAddons), 2, '.', ''),
                    'totalReceived' => $totalPayments,
                    'refunded' => number_format((float) $totalRefund, 2, '.', ''),
                    'voided' => number_format((float) $totalVoided, 2, '.', ''),
                ];
            });

            $payments = $payments->sortByDesc('createdAt')->values();

            return [
                'userId' => $session->user->id,
                'firstName' => $session->user->first_name,
                'openedAt' => $session->opened_at,
                'closedAt' => $session->closed_at,
                'status' => $session->status,
                'payments' => $payments,
            ];
        });

        $response = [
            'data' => $historyData,
            'meta' => [
                'current_page' => $sessions->currentPage(),
                'last_page' => $sessions->lastPage(),
                'per_page' => $sessions->perPage(),
                'total' => $sessions->total(),
                'next_page_url' => $sessions->nextPageUrl(),
                'prev_page_url' => $sessions->previousPageUrl(),
            ],
        ];

        return $this->success('Success, the user\'s cashier history has been opened.', $response);
    }
}
