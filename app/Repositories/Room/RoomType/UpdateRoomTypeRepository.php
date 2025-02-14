<?php

namespace App\Repositories\Room\RoomType;

use App\Models\Amenity\Amenity;
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
            'name' => strtoupper(string: $request->name),
            'description' => $request->description,
            'bed_size' => $request->bedSize,
            'property_size' => $request->propertySize,
            'is_non_smoking' => $request->isNonSmoking,
            'balcony_or_terrace' => $request->balconyOrTerrace,
            'capacity' => $request->capacity
        ]);



        if (isset($request['images']['delete']) && is_array($request['images']['delete'])) {
            foreach ($request['images']['delete'] as $image) {
                // Ensure $image is not empty to avoid deleting unintended records
                if (!empty($image)) {
                    $roomType->images->where('filename', $image)->first()->delete();
                }
            }
        }

        // if (!empty($request['images']['add'])) {

        //     foreach ($request['images']['add'] as $image) {

        //         $imageFilePath = $image->store("public/" . $roomType->reference_number);

        //         RoomTypeImage::create([
        //             'room_type_id' => $roomType->id,
        //             'filename' => basename($imageFilePath)
        //         ]);
        //     }
        // }

        if (isset($request['images']['add']) && is_array($request['images']['add'])) {

            foreach ($request['images']['add'] as $image) {
                if (!empty($image)) {
                    $imageFilePath = $image->store("public/" . $roomType->reference_number);

                    $withFolderFileName = "storage/" . $roomType->reference_number . '/' . basename($imageFilePath);

                    RoomTypeImage::create([
                        'room_type_id' => $roomType->id,
                        'filename' => $withFolderFileName
                    ]);
                }
            }
        }

        // for update images.update.new.* and images.update.old.*
        if (!empty($request['images']['update']['new']) && !empty($request['images']['update']['old'])) {
            foreach ($request['images']['update']['new'] as $key => $newImage) {
                $existingImage = $roomType->images->where('filename', $request['images']['update']['old'][$key])->first();
                if ($existingImage) {
                    $newImageFilePath = $newImage->store("public/" . $roomType->reference_number);
                    $existingImage->update([
                        'filename' => 'storage/' . $roomType->reference_number . '/' . basename($newImageFilePath)
                    ]);
                }
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

        if (isset($request['amenities']['delete']) && is_array($request['amenities']['delete'])) {
            foreach ($request['amenities']['delete'] as $amenity) {
                if (!empty($amenity)) {
                    $amenityId = $this->getAmenityIdFromName(strtoupper($amenity));
                    $roomType->amenities->where('amenity_id', $amenityId)->first()->delete();
                }
            }
        }

        // if (!empty($request['amenities']['add'])) {

        //     foreach ($request['amenities']['add'] as $amenity) {

        //         RoomTypeAmenity::create([
        //             'room_type_id' => $roomType->id,
        //             'amenity_id' => $this->getAmenityIdFromName(strtoupper($amenity))
        //         ]);
        //     }
        // }

        if (isset($request['amenities']['add']) && is_array($request['amenities']['add'])) {
            foreach ($request['amenities']['add'] as $amenity) {
                if (!empty($amenity)) {
                    RoomTypeAmenity::create([
                        'room_type_id' => $roomType->id,
                        'amenity_id' => $this->getAmenityIdFromName(strtoupper($amenity)),
                        'quantity' => $amenity['quantity']
                    ]);
                }
            }
        }

        // Update amenities only
        if (isset($request['amenities']['update']) && is_array($request['amenities']['update'])) {
            foreach ($request['amenities']['update'] as $amenityData) {
                $amenity = $roomType->amenities->where('amenity_id', $this->getAmenityIdFromName(strtoupper($amenityData['name'])))->first();
                if ($amenity) {
                    $amenity->update([
                        'quantity' => $amenityData['quantity']
                    ]);
                }
            }
        }

        // Handle amenities with quantity
        if (!empty($request->amenities)) {
            // Get amenity IDs and prepare sync data
            $syncData = [];

            foreach ($request->amenities as $amenity) {
                $amenityModel = Amenity::where('name', strtoupper($amenity['name']))->firstOrFail();

                if ($amenityModel) {
                    $syncData[$amenityModel->id] = ['quantity' => $amenity['quantity']];
                }
            }

            // Sync amenities with pivot data
            $roomType->amenities()->sync($syncData);
        }

        $roomType = RoomType::where('reference_number', $referenceNumber)->firstOrFail();

        return $this->success("Room type updated successfully.", Arr::collapse([
            $this->getCamelCase($roomType->toArray()),
            [
                'images' => $roomType->images->map(function ($image) use ($roomType) {
                    return "{$image->filename}";
                }),
                'amenities' => $roomType->amenities->map(function ($amenity) {
                    // return $amenity->name;
                    return [
                        'name' => $amenity->name,
                        'quantity' => $amenity->pivot->quantity
                    ];
                }),
                'rates' => $this->getRoomTypeRates($roomType)
            ]
        ]));
    }
}
