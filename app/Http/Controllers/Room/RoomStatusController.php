<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;

use App\Http\Requests\Room\RoomStatus\{IndexRoomStatusRequest, CreateRoomStatusRequest, ShowRoomStatusRequest, UpdateRoomStatusRequest, DeleteRoomStatusRequest};

use App\Repositories\Room\RoomStatus\{IndexRoomStatusRepository, CreateRoomStatusRepository, ShowRoomStatusRepository, UpdateRoomStatusRepository, DeleteRoomStatusRepository};


class RoomStatusController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        IndexRoomStatusRepository   $index,
        CreateRoomStatusRepository  $create,
        ShowRoomStatusRepository    $show,
        UpdateRoomStatusRepository  $update,
        DeleteRoomStatusRepository  $delete
    ) {
        $this->index   = $index;
        $this->create  = $create;
        $this->show    = $show;
        $this->update  = $update;
        $this->delete  = $delete;
    }

    protected function index(IndexRoomStatusRequest $request)
    {
        return $this->index->execute($request);
    }


    protected function create(CreateRoomStatusRequest $request)
    {
        return $this->create->execute($request);
    }


    protected function show(ShowRoomStatusRequest $request, $id)
    {
        return $this->show->execute($id);
    }


    protected function update(UpdateRoomStatusRequest $request, $id)
    {
        return $this->update->execute($request, $id);
    }


    protected function delete(DeleteRoomStatusRequest $request, $id)
    {
        return $this->delete->execute($id);
    }
}
