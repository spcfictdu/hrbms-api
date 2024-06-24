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
    public function execute($request, $referenceNumber)
    {
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

        if (!empty($request['images']['delete'])) {
            // return $roomType->images;
            foreach ($request['images']['delete'] as $image) {

                $roomType->images->where('filename', $image)->first()->delete();
            }
        }

        if (!empty($request['images']['delete'])) {

            foreach ($request['images']['add'] as $image) {

                $imageFilePath = $image->store("public/" . $roomType->reference_number);

                RoomTypeImage::create([
                    'room_type_id' => $roomType->id,
                    'filename' => basename($imageFilePath)
                ]);
            }
        }

        // Assuming $request['images']['update'] contains images to update with their new data
        // if ($request['images']['update']) {
        //     foreach ($request['images']['update'] as $imageData) {
        //         // Assuming $imageData contains 'filename' of the image to update and 'newImage' as the new image file
        //         $existingImage = $roomType->images->where('filename', $imageData['filename'])->first();
        //         if ($existingImage) {
        //             $newImageFilePath = $imageData['newImage']->store("public/" . $roomType->reference_number);
        //             $existingImage->update([
        //                 'filename' => basename($newImageFilePath)
        //             ]);
        //         }
        //     }
        // }

        // if ($request['images']) {

        //     foreach ($request['images'] as $image) {

        //         $imageFilePath = $image->store("public/" . $roomType->reference_number);

        //         RoomTypeImage::create([
        //             'room_type_id' => $roomType->id,
        //             'filename' => 'storage/' . $roomType->reference_number . '/' . basename($imageFilePath)
        //         ]);
        //     }

        //     // Artisan::call(`storage:folder-access {$roomType->reference_number}`);
        //     // Artisan::call("storage:folder-access " . $roomType->reference_number);
        // }

        if (!empty($request['amenities']['delete'])) {

            foreach ($request['amenities']['delete'] as $amenity) {

                $roomType->amenities->where('amenity_id', $this->getAmenityIdFromName(strtoupper($amenity)))->first()->delete();
            }
        }

        if (!empty($request['amenities']['add'])) {

            foreach ($request['amenities']['add'] as $amenity) {

                RoomTypeAmenity::create([
                    'room_type_id' => $roomType->id,
                    'amenity_id' => $this->getAmenityIdFromName(strtoupper($amenity))
                ]);
            }
        }

        $roomType = RoomType::where('reference_number', $referenceNumber)->firstOrFail();

        return $this->success("Room type updated successfully.", Arr::collapse([
            $this->getCamelCase($roomType->toArray()),
            [
                'images' => $roomType->images->map(function ($image) use ($roomType) {
                    return "{$roomType->reference_number}/{$image->filename}";
                }),
                'amenities' => $roomType->amenities->pluck('amenity')->map(function ($amenity) {
                    return $amenity->name;
                }),
                'rates' => $this->getRoomTypeRates($roomType)
            ]
        ]));
    }
}
