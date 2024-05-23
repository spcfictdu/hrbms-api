<?php

namespace App\Http\Controllers\Enum;

use App\Http\Controllers\Controller;

use App\Http\Requests\Enum\{RoomTypeEnumRequest, RoomNumberEnumRequest, RoomEnumRequest};


use App\Repositories\Enum\{RoomNumberEnumRepository, RoomTypeEnumRepository, RoomEnumRepository};

class EnumController extends Controller
{
    protected $roomTypeEnum, $roomNumberEnum, $roomEnum;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        RoomTypeEnumRepository $roomTypeEnum,
        RoomNumberEnumRepository $roomNumberEnum,
        RoomEnumRepository $roomEnum
    ) {
        $this->roomTypeEnum = $roomTypeEnum;
        $this->roomNumberEnum = $roomNumberEnum;
        $this->roomEnum = $roomEnum;
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
}
