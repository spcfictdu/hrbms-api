<?php

namespace App\Http\Controllers\CashierSession;

use App\Http\Controllers\Controller;
use App\Models\CashierSession\CashierSession;
use App\Models\Transaction\Payment;
use App\Models\User;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class UserCashierController extends Controller
{
    use ResponseAPI;
    public function startSession(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        // Check if user has an opened cashier,
        // If there is, they cannot create another

        // if ($user->cashierSessions)
        $activeSession = $user->cashierSessions->where('status', 'ACTIVE')->first();
        if ($activeSession) {
            return $this->error("User already has an active cashier");
        }

        $request->validate([
            'openingBalance' => 'required|integer|min:0'
        ]);

        $cashierSession = CashierSession::create([
            'user_id' => $userId,
            'opening_balance' => $request->openingBalance,
            'opened_at' => Date::now(),
            'status' => 'ACTIVE'
        ]);

        return $this->success('Success', $cashierSession);
    }

    public function showSession()
    {
        $user = auth()->user();

        $userActiveCashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();

        // If not found
        if (!$userActiveCashierSession) {
            return $this->error('User has no active cashier');
        }

        $openingBalance = $userActiveCashierSession->opening_balance;

        // data = { drawerCash: $$$, payments: [{name: gcash, amount: $$$}, {name: cash, amount: $$$}...]}
        // $allPaymentTypes = DB::table('payment_methods')->pluck('name')->toArray();

        $allPaymentTypes = ['CASH', 'GCASH', 'CHEQUE', 'CREDIT_CARD'];

        $payments = $userActiveCashierSession->payments->groupBy('payment_type')->map(function ($payments, $types) {
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

        $data = [
            'drawerCash' => $openingBalance,
            'payments' => $payments,
        ];

        return $this->success('Success, the user\'s cashier has been opened.', $data);
    }

    public function closeSession(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'closingBalance' => 'required|numeric|min:0',
        ]);

        $userActiveCashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();

        // Calculate the total payments received
        $totalPayments = $userActiveCashierSession->payments->sum('amount_received');

        $openingBalance = $userActiveCashierSession->opening_balance;

        $total = $totalPayments + $openingBalance;

        // Check if the total matches the closing balance
        if ($total !== (float) $request->closingBalance) {
            return $this->error('Total and closing balance are not equal', 500, $total,);
        }

        $userActiveCashierSession->update([
            'closing_balance' => $request->closingBalance,
            'closed_at' => Date::now(),
            'status' => 'INACTIVE'
        ]);

        return $this->success('The user\'s cashier has been closed.');
    }

    public function showHistory($userId) {
        $user = User::find($userId);

        if (!$user) {
            return $this->error('User not found.');
        }

        $userCashierSessions = CashierSession::with('payments', 'user')
            ->where('user_id', $userId)
            ->get();

        if ($userCashierSessions->isEmpty()) {
            return $this->error('User has no cashier history.');
        }

        $historyData = [];

        foreach ($userCashierSessions as $cashierSession) {
            $payments = $cashierSession->payments->map(function ($payment) {
                $discountValue = 0;
    
                if (isset($payment->voucherDiscount)) {
                    $discountValue = $payment->voucherDiscount->value ?? 0;
                } elseif(isset($payment->seniorPwdDiscount)) {
                    $discountValue = $payment->seniorPwdDiscount->value ?? 0;
                }  else{
                    $discountValue = 0;
                }

                $discount = $payment->transaction->room_total*$discountValue;

                return [
                    'paymentId' => $payment->id,
                    'guestName' => $payment->transaction->guest->full_name,
                    'paymentType' => $payment->payment_type,
                    'amountReceived' => number_format((float) $payment->amount_received, 2, '.', ''),
                    'roomTotal' => number_format((float) $payment->transaction->room_total, 2, '.', ''),
                    'addOnTotal' => $payment->transaction->bookingAddon->sum('total_price'),
                    'discount' => number_format((float) $discount, 2, '.', ''),
                    'createdAt' => $payment->created_at,
                ];
            });

            $historyData[] = [
                'userId' => $cashierSession->user->id,
                'openedAt' => $cashierSession->opened_at,
                'closedAt' => $cashierSession->closed_at,
                'status' => $cashierSession->status,
                'payments' => $payments,
            ];
        }

        return $this->success('Success, the user\'s cashier history has been opened.', $historyData);
    }
}
