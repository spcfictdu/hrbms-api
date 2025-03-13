<?php

namespace App\Repositories\Addon;

use App\Models\amenity\Addon;
use App\Repositories\BaseRepository;

class IndexAddonRepository extends BaseRepository
{
    public function execute(){
        $addons = Addon::all();
        
        return $this->success( 
            'list of all addons',
            $addons->map(function($addon){
                return [
                    'name' => $addon->name,
                    'price' => $addon->price

                    ];
                }
            )   
        );
    }
}
