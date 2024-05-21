<?php

namespace App\Http\Controllers\Enum;

use App\Http\Controllers\Controller;

use App\Http\Requests\Enum\{RoomTypeEnumRequest, RoomNumberEnumRequest};


use App\Repositories\Enum\{RoomNumberEnumRepository, RoomTypeEnumRepository};

class EnumController extends Controller
{
    protected $roomTypeEnum, $roomNumberEnum;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        RoomTypeEnumRepository $roomTypeEnum,
        RoomNumberEnumRepository $roomNumberEnum,
    ) {
        $this->roomTypeEnum = $roomTypeEnum;
        $this->roomNumberEnum = $roomNumberEnum;
    }

    public function roomTypeEnum(RoomTypeEnumRequest $request)
    {
        return $this->roomTypeEnum->execute();
    }

    protected function roomNumberEnum(RoomNumberEnumRequest $request)
    {
        return $this->roomNumberEnum->execute($request);
    }
}
