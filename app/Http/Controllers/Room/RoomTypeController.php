<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;

// * REQUEST
use App\Http\Requests\Room\RoomType\{
    IndexRoomTypeRequest,
    CreateRoomTypeRequest,
    ShowRoomTypeRequest,
    UpdateRoomTypeRequest
};

// * REPOSITORY
use App\Repositories\Room\RoomType\{
    IndexRoomTypeRepository,
    CreateRoomTypeRepository,
    ShowRoomTypeRepository,
    UpdateRoomTypeRepository
};

class RoomTypeController extends Controller
{
    protected $index, $create, $show, $update;

    public function __construct(
        IndexRoomTypeRepository $index,
        CreateRoomTypeRepository $create,
        ShowRoomTypeRepository $show,
        UpdateRoomTypeRepository $update
    ) {
        $this->index = $index;
        $this->create = $create;
        $this->show = $show;
        $this->update = $update;
    }

    protected function index(IndexRoomTypeRequest $request)
    {
        return $this->index->execute();
    }

    protected function create(CreateRoomTypeRequest $request)
    {
        return $this->create->execute($request);
    }

    protected function show(ShowRoomTypeRequest $request, $referenceNumber)
    {
        return $this->show->execute($referenceNumber);
    }

    protected function update(UpdateRoomTypeRequest $request, $referenceNumber)
    {
        return $this->update->execute($request, $referenceNumber);
    }
}
