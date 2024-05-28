<?php

namespace App\Repositories\Transaction\Guest;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\{Arr, Str};

use Carbon\Carbon;


class ShowGuestTransactionRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $transaction = Transaction::where('reference_number', $referenceNumber)->first();

        $data = [
            'referenceNumber' => $transaction->referenceNumber,
            'guest' => $transaction->guest,
        ];

        return $data;
    }
}
