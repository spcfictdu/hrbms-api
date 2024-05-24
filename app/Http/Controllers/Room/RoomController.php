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

// * REPOSITORY
use App\Repositories\Room\Room\{
    IndexRoomRepository,
    CreateRoomRepository,
    ShowRoomRepository,
    UpdateRoomRepository,
    DeleteRoomRepository
};

class RoomController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    public function __construct(
        IndexRoomRepository $index,
        CreateRoomRepository $create,
        ShowRoomRepository $show,
        UpdateRoomRepository $update,
        DeleteRoomRepository $delete
    ) {
        $this->index = $index;
        $this->create = $create;
        $this->show = $show;
        $this->update = $update;
        $this->delete = $delete;
    }

    protected function index(IndexRoomRequest $request)
    {
        return $this->index->execute($request);
    }

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
}
