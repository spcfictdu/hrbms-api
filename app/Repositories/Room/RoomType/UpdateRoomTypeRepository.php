<?php

namespace App\Repositories\Room\RoomType;

use App\Repositories\BaseRepository;

use Illuminate\Support\Arr;

use App\Models\Room\{
    RoomType,
    RoomTypeImage,
    RoomTypeAmenity
};

class UpdateRoomTypeRepository extends BaseRepository
{
    public function execute($request, $referenceNumber){

        // return $request['amenities']['delete'];

        $roomType = RoomType::where('reference_number', $referenceNumber)->firstOrFail();

        $roomType->update([
            'name' => strtoupper($request->name),
            'description' => $request->description,
            'bed_size' => $request->bedSize,
            'property_size' => $request->propertySize,
            'is_non_smoking' => $request->isNonSmoking,
            'balcony_or_terrace' => $request->balconyOrTerrace,
            'capacity' => $request->capacity
        ]);

        if($request['images']['delete']){

            foreach($request['images']['delete'] as $image){

                // return $roomType->images;

                $roomType->images->where('filename', $image)->first()->delete();
            }
        }

        if($request['images']['add']){

            foreach($request['images']['add'] as $image){

                $imageFilePath = $image->store("public/" . $roomType->reference_number);

                RoomTypeImage::create([
                    'room_type_id' => $roomType->id,
                    'filename' => basename($imageFilePath)
                ]);
            }
        }

        if($request['amenities']['delete']){

            foreach($request['amenities']['delete'] as $amenity){

                $roomType->amenities->where('amenity_id', $this->getAmenityIdFromName(strtoupper($amenity)))->first()->delete();
            }
        }

        if($request['amenities']['add']){

            foreach($request['amenities']['add'] as $amenity){

                RoomTypeAmenity::create([
                    'room_type_id' => $roomType->id,
                    'amenity_id' => $this->getAmenityIdFromName(strtoupper($amenity))
                ]);
            }
        }

        return $this->success("Room type updated successfully.", Arr::collapse([
            $this->getCamelCase($roomType->toArray()),
            [
                'images' => $roomType->images->map(function ($image) use ($roomType) {
                    return "{$roomType->reference_number}/{$image->filename}";
                }),
                'amenities' => $roomType->amenities->pluck('amenity')->map(function ($amenity) {
                    return $amenity->name;
                })
            ]
        ]));
    }
}
