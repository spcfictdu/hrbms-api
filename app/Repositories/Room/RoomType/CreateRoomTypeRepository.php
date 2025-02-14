<?php

namespace App\Repositories\Room\RoomType;

use App\Repositories\BaseRepository;

use Illuminate\Support\Arr;

use App\Models\Room\{
    RoomType,
    RoomTypeImage,
    RoomTypeAmenity,
    RoomTypeRate
};
use Exception;
use Illuminate\Support\Facades\Artisan;

class CreateRoomTypeRepository extends BaseRepository
{
    public function execute($request)
    {


        $roomType = RoomType::create([
            'reference_number' => $this->roomTypeReferenceNumber(),
            'name' => strtoupper($request->name),
            'description' => $request->description,
            'bed_size' => $request->bedSize,
            'property_size' => $request->propertySize,
            'is_non_smoking' => $request->isNonSmoking,
            'balcony_or_terrace' => $request->balconyOrTerrace,
            'capacity' => $request->capacity,
            'extra_person_capacity' => $request->extraPersonCapacity ?? '0'
        ]);

        if ($request['images']) {

            foreach ($request['images'] as $image) {

                $imageFilePath = $image->store("public/" . $roomType->reference_number);

                RoomTypeImage::create([
                    'room_type_id' => $roomType->id,
                    'filename' => 'storage/' . $roomType->reference_number . '/' . basename($imageFilePath)
                ]);
            }

            // Artisan::call(`storage:folder-access {$roomType->reference_number}`);
            Artisan::call("storage:folder-access " . $roomType->reference_number);
        }

        // Check if amenities are provided, then insert them into the pivot table
        if (isset($request['amenities']) && is_array($request['amenities'])) {
            $amenitiesData = [];
            foreach ($request['amenities'] as $amenity) {
                $amenityId = $this->getAmenityIdFromName(strtoupper($amenity['name']));
                if ($amenityId) {
                    $amenitiesData[] = [
                        'room_type_id' => $roomType->id,
                        'amenity_id' => $amenityId,
                        'quantity' => $amenity['quantity']
                    ];
                } else {
                    // Handle the case where amenity ID is not found
                    throw new Exception("Amenity ID not found for amenity name: " . $amenity['name']);
                }
            }
            if (!empty($amenitiesData)) {
                RoomTypeAmenity::insert($amenitiesData); // Batch insert
            }
        }
        if ($request['rates']) {

            RoomTypeRate::create([
                'reference_number' => $this->ratesReferenceNumber(),
                'room_type_id' => $roomType->id,
                'type' => 'REGULAR',
                'monday' => $request['rates']['monday'],
                'tuesday' => $request['rates']['tuesday'],
                'wednesday' => $request['rates']['wednesday'],
                'thursday' => $request['rates']['thursday'],
                'friday' => $request['rates']['friday'],
                'saturday' => $request['rates']['saturday'],
                'sunday' => $request['rates']['sunday']
            ]);
        }

        return $this->success("Room type created successfully.", Arr::collapse([
            $this->getCamelCase($roomType->toArray()),
            [
                'images' => $roomType->images->map(function ($image) use ($roomType) {
                    return "{$roomType->reference_number}/{$image->filename}";
                }),
                'amenities' => $roomType->amenities->map(function ($amenity) {
                    // return $amenity->name;
                    return [
                        'name' => $amenity->name,
                        'quantity' => $amenity->pivot->quantity,
                    ];
                }),
                'rates' => $roomType->rates->where('type', 'REGULAR')->flatMap(function ($rate) {
                    return [
                        'monday' => $rate->monday,
                        'tuesday' => $rate->tuesday,
                        'wednesday' => $rate->wednesday,
                        'thursday' => $rate->thursday,
                        'friday' => $rate->friday,
                        'saturday' => $rate->saturday,
                        'sunday' => $rate->sunday
                    ];
                })
            ]
        ]));
    }
}
