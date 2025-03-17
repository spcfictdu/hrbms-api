<?php

namespace App\Repositories\Addon;

use App\Models\Amenity\Addon;
use App\Repositories\BaseRepository;

class UpdateAddonRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
         $addon = Addon::where('reference_number', $referenceNumber)->firstOrFail();

        // if($request->name){
        //     $addon->update([
        //         'name' => strtoupper($request->name),
        //         'price' => $request->price ?? 0
        //     ]);
        // }else{
        //     $addon->update([
        //         'price' => $request->price ?? 0
        //     ]);
        // }
         $addon->update([
                'name' => strtoupper($request->name),
                'price' => $request->price ?? 0
            ]);

        return $this->success("Addon updated successfully.", $this->getCamelCase($addon->toArray()));
    }
}