<?php

namespace App\Repositories\Voucher;

use App\Models\Discount\Voucher;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

class IndexVoucherRepository extends BaseRepository
{
    public function execute()
    {
        $vouchers = DB::table('vouchers')->get();
        return $this->success(
            'list of all vouchers', $vouchers->map(function($voucher){
                return [
                    'code' => $voucher->code,
                    'discount' => ($voucher->value*100 . '%'),
                    'usage' => $voucher->usage,
                    'status' => $voucher->status
                ];
                
            })); 

    }
}