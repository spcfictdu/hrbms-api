<?php

namespace App\Repositories\Transaction\Guest;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\{Arr, Str};

use Carbon\Carbon;


class CreateGuestTransactionRepository extends BaseRepository
{
    public function execute()
    {
        return 1;
    }
}
