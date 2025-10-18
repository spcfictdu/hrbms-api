<?php

namespace App\Repositories\Transaction\Miscellaneous;

use App\Repositories\BaseRepository;
use App\Models\PaymentType\Bank;

class IndexBankRepository extends BaseRepository
{
    public function execute(){
        return $this->success('Banks fetched successfully', Bank::all());
    }
}
