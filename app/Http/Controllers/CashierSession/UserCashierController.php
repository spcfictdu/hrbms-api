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



        return $this->success('Success', $data);
    }

    public function closeSession(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'closingBalance' => 'required',
        ]);

        $userActiveCashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();

        // Calculate the total payments received
        $totalPayments = $userActiveCashierSession->payments->sum('amount_received');

        $openingBalance = $userActiveCashierSession->opening_balance;

        $total = $totalPayments + $openingBalance;

        // Check if the total matches the closing balance
        if ($total !== $request->closingBalance) {
            return $this->error('Total and closing balance are not equal');
        }

        $userActiveCashierSession->update([
            'closing_balance' => $request->closingBalance,
            'closed_at' => Date::now(),
            'status' => 'INACTIVE'
        ]);

        return $this->success('Success', $userActiveCashierSession);
    }
}
