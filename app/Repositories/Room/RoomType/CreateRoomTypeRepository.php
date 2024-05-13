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
            'capacity' => $request->capacity
        ]);

        if ($request['images']) {

            foreach ($request['images'] as $image) {

                $imageFilePath = $image->store("public/" . $roomType->reference_number);

                RoomTypeImage::create([
                    'room_type_id' => $roomType->id,
                    'filename' => basename($imageFilePath)
                ]);
            }
        }

        if ($request['amenities']) {
            foreach ($request['amenities'] as $amenity) {

                RoomTypeAmenity::create([
                    'room_type_id' => $roomType->id,
                    'amenity_id' => $this->getAmenityIdFromName(strtoupper($amenity))
                ]);
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
                'amenities' => $roomType->amenities->pluck('amenity')->map(function ($amenity) {
                    return $amenity->name;
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
