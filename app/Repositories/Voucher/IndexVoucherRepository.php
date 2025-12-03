<?php

namespace App\Repositories\Voucher;

use App\Models\Discount\Voucher;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

class IndexVoucherRepository extends BaseRepository
{
    public function execute()
    {
        $vouchers = Voucher::all();
        $transformedVouchers = $vouchers->map(function ($voucher) {
            return [
                'referenceNumber' => $voucher->reference_number,
                'code' => $voucher->code,
                'expiresAt' => $voucher->expires_at,
                'discount' => ($voucher->value*100 . '%'),
                'usage' => $voucher->usage,
                'status' => $voucher->status
            ];
        });
        return $this->success('List of all vouchers', $transformedVouchers);
    }
}
