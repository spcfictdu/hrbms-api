<?php

namespace App\Http\Controllers\Enum;

use App\Http\Controllers\Controller;

use App\Http\Requests\Enum\{RoomTypeEnumRequest, RoomNumberEnumRequest, RoomEnumRequest, RoomTypeAmenityEnumRequest, RoomTypeRateEnumRequest, GuestAvailableRoomNumbersEnumRequest};
use App\Models\Guest\Guest;
use App\Repositories\Enum\{RoomNumberEnumRepository, RoomTypeEnumRepository, RoomEnumRepository, GuestEnumRepository, RoomTypeAmenityEnumRepository, RoomTypeRateEnumRepository};
use Illuminate\Http\Request;

class EnumController extends Controller
{
    protected $roomTypeEnum, $roomNumberEnum, $roomEnum, $guestEnum, $roomTypeAmenityEnum, $roomTypeRateEnum;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        RoomTypeEnumRepository $roomTypeEnum,
        RoomNumberEnumRepository $roomNumberEnum,
        RoomEnumRepository $roomEnum,
        GuestEnumRepository $guestEnum,
        RoomTypeAmenityEnumRepository $roomTypeAmenityEnum,
        RoomTypeRateEnumRepository $roomTypeRateEnum

    ) {
        $this->roomTypeEnum = $roomTypeEnum;
        $this->roomNumberEnum = $roomNumberEnum;
        $this->roomEnum = $roomEnum;
        $this->guestEnum = $guestEnum;
        $this->roomTypeAmenityEnum = $roomTypeAmenityEnum;
        $this->roomTypeRateEnum = $roomTypeRateEnum;
    }

    public function roomTypeEnum(RoomTypeEnumRequest $request)
    {
        return $this->roomTypeEnum->execute();
    }

    protected function roomNumberEnum(RoomNumberEnumRequest $request)
    {
        return $this->roomNumberEnum->execute($request);
    }

    protected function roomEnum(RoomEnumRequest $request)
    {
        return $this->roomEnum->execute($request);
    }

    public function guestEnum()
    {
        return $this->guestEnum->execute();
    }

    public function roomTypeAmenityEnum(RoomTypeAmenityEnumRequest $request)
    {
        return $this->roomTypeAmenityEnum->execute($request);
    }

    public function roomTypeRateEnum(RoomTypeRateEnumRequest $request)
    {
        return $this->roomTypeRateEnum->execute($request);
    }

    public function guestAvailableRoomNumbersEnum(GuestAvailableRoomNumbersEnumRequest $request)
    {
        return $this->guestEnum->availableRoomNumbers($request);
    }
}
