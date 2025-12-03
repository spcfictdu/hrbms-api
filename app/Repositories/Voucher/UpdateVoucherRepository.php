<?php

namespace App\Repositories\Voucher;

use App\Models\Discount\Voucher;
use App\Repositories\BaseRepository;

class UpdateVoucherRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
        $voucher = Voucher::where('reference_number', $referenceNumber)->firstOrFail();

        if (isset($request->status)) {
            $voucher->update([
                'status' => $request->status
            ]);
        } else {
            $voucher->update([
                'code' => $request->code ?? $voucher->code,
                'value' => $request->value/100 ?? $voucher->value,
                'usage' => $request->usage ?? $voucher->usage,
                'expires_at' => $request->expiresAt ?? $voucher->expires_at
            ]);
        }

        return $this->success('Voucher updated', 
                 [
                    'referenceNumber' => $voucher->reference_number,
                    'code' => $voucher->code,
                    'discount' => ($voucher->value*100 . '%'),
                    'usage' => $voucher->usage,
                    'status' => $voucher->status,
                    'expiresAt' => $voucher->expires_at
                 ]
            );
    }
}