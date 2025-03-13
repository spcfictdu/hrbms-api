<?php

namespace App\Repositories\Discount;

use App\Models\Discount\Discount;
use App\Repositories\BaseRepository;

class IndexDiscountRepository extends BaseRepository
{
    public function execute(){
        $discounts = Discount::all();
        
        return $this->success(
            'list of all discounts',
            $discounts->map(function($discount){
                return[
                    'name' => $discount->name,
                    'discount' => ($discount->value*100 . '%')
                ];
            }
        ));
    }
}
