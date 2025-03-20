<?php

namespace App\Repositories\CashierSession;

use App\Models\CashierSession\CashierSession;
use App\Repositories\BaseRepository;

class IndexCashierSessionRepository extends BaseRepository
{
    public function execute()
    {
        $cashierSessions = CashierSession::all();

        return $this->success("Successfully retrieved cashier sessions", $cashierSessions);
    }
}
