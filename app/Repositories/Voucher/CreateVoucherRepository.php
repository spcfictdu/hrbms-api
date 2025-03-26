<?php

namespace App\Repositories\Voucher;

use App\Models\Discount\Voucher;
use App\Repositories\BaseRepository;

class CreateVoucherRepository extends BaseRepository
{
    public function execute($request)
    {
        $voucher = Voucher::create([
            'reference_number' => $this->voucherReferenceNumber(),
            'code' => $request->code,
            'value' => $request->value,
            'usage' => $request->usage,
            'status' => 'ACTIVE',
            'expires_at' => $request->expires_at
        ]);
    }
}   