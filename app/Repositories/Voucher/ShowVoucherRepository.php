<?php

namespace App\Repositories\Voucher;

use App\Models\Discount\Voucher;
use App\Repositories\BaseRepository;

class ShowVoucherRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $voucher = Voucher::where('reference_number', $referenceNumber)->firstOrFail();
        return $this->success('Voucher found', 
                 [
                    'referenceNumber' => $referenceNumber,
                    'code' => $voucher->code,
                    'discount' => ($voucher->value*100 . '%'),
                    'usage' => $voucher->usage,
                    'status' => $voucher->status,
                    'expiresAt' => $voucher->expires_at
                 ]
            );
        
    }
}