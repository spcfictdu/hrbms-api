<?php

namespace App\Repositories\Transaction\Miscellaneous;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\Arr;


class DeleteReservationRepository extends BaseRepository
{
    public function execute($status, $referenceNumber)
    {
        try{
        $transaction = Transaction::where('reference_number', $referenceNumber)->where('status', $status)->first();
        
        $transaction->forceDelete();

        return $this->success("Reservation Successfully Deleted");
    } catch (\Exception $e) {
        return $this->error("Error: " . $e->getMessage(), 500, [], false);
    }
    }
}
