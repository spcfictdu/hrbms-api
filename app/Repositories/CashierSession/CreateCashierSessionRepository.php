<?php

namespace App\Repositories\CashierSession;

use App\Models\CashierSession\CashierSession;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Date;

class CreateCashierSessionRepository extends BaseRepository
{
    public function execute($request)
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

        $cashierSession = CashierSession::create([
            'user_id' => $userId,
            'opening_balance' => $request->openingBalance,
            'opened_at' => Date::now(),
            'status' => 'ACTIVE'
        ]);

        return $this->success("Success", $cashierSession);
    }
}
