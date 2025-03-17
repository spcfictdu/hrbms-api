<?php

namespace App\Repositories\Addon;

use App\Models\Amenity\Addon;
use App\Models\Amenity\BookingAddOn;
use App\Repositories\BaseRepository;
use App\Models\Transaction\Transaction;

class DeleteAddonRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $addon = Addon::where('reference_number', $referenceNumber)->firstOrFail();
        $bookingAddon = BookingAddOn::where('name',$addon->name)->get();
        $transactionStatus = Transaction::wherein('id', $bookingAddon->pluck('transaction_id'))->get('status');

        if ($bookingAddon->isNotEmpty()) {
            foreach($transactionStatus as $status){
                if($status === 'RESERVED' || $status === 'CHECKED-IN' || $status === 'CONFIRMED'){
                    return $this->error('Cannot delete addon that is currently in use');
                }
            }
        } else {
            $addon->delete();
        }

        return $this->success("Addon deleted successfully.");
    }
}