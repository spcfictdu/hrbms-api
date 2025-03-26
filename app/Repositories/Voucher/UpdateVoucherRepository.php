<?php

namespace App\Repositories\Voucher;

use App\Models\Discount\Voucher;
use App\Repositories\BaseRepository;

class UpdateVoucherRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
        $voucher = Voucher::where('reference_number', $referenceNumber)->firstOrFail();
        $voucherUsage = $request->usage?? $voucher->usage;

        if($request->status){
            $voucherStatus = $request->status;
            if($request->status === 'ACTIVATE' && $voucherUsage >= 1){
                $voucher->update([
                    'code' => $request->code,
                    'value' => $request->value,
                    'usage' => $voucherUsage,
                    'status' => $voucherStatus
                ]);
            }else{
                return $this->error('Voucher is redeemed');
            }
        }else{
            $voucherStatus = $voucherUsage < 1 ? 'INACTIVE' : 'ACTIVE';
            $voucher->update([
                'code' => $request->code,
                'value' => $request->value,
                'usage' => $voucherUsage,
                'status' => $voucherStatus
            ]);
        }  
    }
}