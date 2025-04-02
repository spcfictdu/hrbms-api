<?php

namespace App\Repositories\Voucher;

use App\Models\Discount\Voucher;
use App\Repositories\BaseRepository;

class UpdateVoucherRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
        $voucher = Voucher::where('reference_number', $referenceNumber)->firstOrFail();
        $voucherCode = $request->code ?? $voucher->code;
        $voucherValue = $request->value ? $request->value / 100 :$voucher->value;
        $voucherUsage = $request->usage ?? $voucher->usage;

        if($request->status){
            if($request->status === 'ACTIVE' && $voucherUsage >= 1){
                $voucher->update([
                    'code' => $voucherCode,
                    'value' => $voucherValue,
                    'usage' => $voucherUsage,
                    'status' => $request->status
                ]);
            }else{
                $voucher->update([
                    'code' => $voucherCode,
                    'value' => $voucherValue,
                    'usage' => $voucherUsage,
                    'status' => $request->status
                ]);
            }
        }else{
            $voucherStatus = $voucherUsage < 1 ? 'INACTIVE' : 'ACTIVE';
            $voucher->update([
                'code' => $voucherCode,
                'value' => $voucherValue,
                'usage' => $voucherUsage,
                'status' => $voucherStatus
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