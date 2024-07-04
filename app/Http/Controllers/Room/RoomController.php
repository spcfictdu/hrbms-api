<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;

// * REQUEST
use App\Http\Requests\Room\Room\{
    IndexRoomRequest,
    CreateRoomRequest,
    ShowRoomRequest,
    UpdateRoomRequest,
    DeleteRoomRequest
};

use App\Http\Requests\Room\Room\Miscellaneous\{FilterHotelRoomsRequest, ShowHotelRoomRequest};

// * REPOSITORY
use App\Repositories\Room\Room\{
    IndexRoomRepository,
    CreateRoomRepository,
    ShowRoomRepository,
    UpdateRoomRepository,
    DeleteRoomRepository,
};

use App\Repositories\Room\Room\Miscellaneous\{IndexHotelRoomsRepository, FilterHotelRoomsRepository, ShowHotelRoomsRepository};

class RoomController extends Controller
{
    protected $index, $create, $show, $update, $delete, $hotelRoom, $searchHotelRoom, $roomInfo;

    public function __construct(
        IndexRoomRepository $index,
        CreateRoomRepository $create,
        ShowRoomRepository $show,
        UpdateRoomRepository $update,
        DeleteRoomRepository $delete,
        IndexHotelRoomsRepository $hotelRoom,
        FilterHotelRoomsRepository $searchHotelRoom,
        ShowHotelRoomsRepository $roomInfo
    ) {
        $this->index = $index;
        $this->create = $create;
        $this->show = $show;
        $this->update = $update;
        $this->delete = $delete;
        $this->hotelRoom = $hotelRoom;
        $this->searchHotelRoom = $searchHotelRoom;
        $this->roomInfo = $roomInfo;
    }

    protected function index(IndexRoomRequest $request)
    {
        return $this->index->execute($request);
    }

    // protected function indexImage(IndexRoomRequest $request)
    // {
    //     return $this->index->executeImage($request);
    // }

    protected function create(CreateRoomRequest $request)
    {
        return $this->create->execute($request);
    }

    protected function show(ShowRoomRequest $request, $referenceNumber)
    {
        return $this->show->execute($referenceNumber);
    }

    protected function update(UpdateRoomRequest $request, $referenceNumber)
    {
        return $this->update->execute($request, $referenceNumber);
    }

    protected function delete(DeleteRoomRequest $request, $referenceNumber)
    {
        return $this->delete->execute($referenceNumber);
    }

    protected function hotelRoom() {
        return $this->hotelRoom->execute();
    }

    protected function searchHotelRoom(FilterHotelRoomsRequest $request){
        return $this->searchHotelRoom->execute($request);
    }

    protected function roomInfo(ShowHotelRoomRequest $request, $referenceNumber){
        return $this->roomInfo->execute($request, $referenceNumber);
    }
}
