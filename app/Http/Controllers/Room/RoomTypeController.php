<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;

// * REQUEST
use App\Http\Requests\Room\RoomType\{
    IndexRoomTypeRequest,
    CreateRoomTypeRequest,
    ShowRoomTypeRequest,
    UpdateRoomTypeRequest,
    DeleteRoomTypeRequest
};

// * REPOSITORY
use App\Repositories\Room\RoomType\{
    IndexRoomTypeRepository,
    CreateRoomTypeRepository,
    ShowRoomTypeRepository,
    UpdateRoomTypeRepository,
    DeleteRoomTypeRepository
};

class RoomTypeController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    public function __construct(
        IndexRoomTypeRepository $index,
        CreateRoomTypeRepository $create,
        ShowRoomTypeRepository $show,
        UpdateRoomTypeRepository $update,
        DeleteRoomTypeRepository $delete
    ) {
        $this->index = $index;
        $this->create = $create;
        $this->show = $show;
        $this->update = $update;
        $this->delete = $delete;
    }

    protected function index(IndexRoomTypeRequest $request)
    {
        return $this->index->execute($request);
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

    protected function delete(DeleteRoomTypeRequest $request, $referenceNumber)
    {
        return $this->delete->execute($referenceNumber);
    }
}
