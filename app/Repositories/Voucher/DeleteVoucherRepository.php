<?php

namespace App\Repositories\Voucher;

use App\Models\Discount\Voucher;
use App\Repositories\BaseRepository;

class DeleteVoucherRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $voucher = Voucher::where('reference_number', $referenceNumber)->firstOrFail();
        if($voucher->status === "ACTIVE"){
            return $this->error('Voucher is active');
        }

        $voucher->delete();
        return $this->success('Voucher deleted successfully', $voucher);
    }
}