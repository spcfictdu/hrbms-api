<?php
namespace App\Repositories\Transaction\Booking;

use App\Models\Transaction\{Transaction};

use Carbon\Carbon;

use Illuminate\Support\{Str, Arr};
use App\Repositories\BaseRepository;

class EditBookingRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $transaction = Transaction::where('reference_number', $referenceNumber)->first();

        if($transaction){
            $guest = $transaction->guest;
            
            $payment = $transaction->payment;

            return $this->success('Edit booking check-in')
        }
    }
}
