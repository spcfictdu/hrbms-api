<?php

namespace App\Http\Controllers\CashierSession;

use App\Http\Controllers\Controller;
use App\Models\CashierSession\CashierSession;
use App\Models\User;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

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
        $activeSession = $user->cashierSessions->where('status', 'ACTIVE');
        if ($activeSession) {
            return $this->error("User already has an active cashier");
        }

        $request->validate([
            'user_id' => $userId,
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

        return $this->success('Success', $userActiveCashierSession);
    }

    public function closeSession(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'closingBalance' => 'required',
        ]);

        $userActiveCashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();

        $userActiveCashierSession->update([
            'closing_balance' => $request->closingBalance,
            'closed_at' => Date::now(),
            'status' => 'INACTIVE'
        ]);

        return $this->success('Success', $userActiveCashierSession);
    }
}
