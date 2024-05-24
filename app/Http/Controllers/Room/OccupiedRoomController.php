<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;

use App\Http\Requests\Room\OccupiedRoom\{IndexOccupiedRoomRequest, CreateOccupiedRoomRequest, ShowOccupiedRoomRequest, UpdateOccupiedRoomRequest, DeleteOccupiedRoomRequest};

use App\Repositories\Room\OccupiedRoom\{IndexOccupiedRoomRepository, CreateOccupiedRoomRepository, ShowOccupiedRoomRepository, UpdateOccupiedRoomRepository, DeleteOccupiedRoomRepository};


class OccupiedRoomController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        IndexOccupiedRoomRepository   $index,
        CreateOccupiedRoomRepository  $create,
        ShowOccupiedRoomRepository    $show,
        UpdateOccupiedRoomRepository  $update,
        DeleteOccupiedRoomRepository  $delete
    ) {
        $this->index   = $index;
        $this->create  = $create;
        $this->show    = $show;
        $this->update  = $update;
        $this->delete  = $delete;
    }

    protected function index(IndexOccupiedRoomRequest $request)
    {
        return $this->index->execute();
    }


    protected function create(CreateOccupiedRoomRequest $request)
    {
        return $this->create->execute($request);
    }


    protected function show(ShowOccupiedRoomRequest $request, $id)
    {
        return $this->show->execute($id);
    }


    protected function update(UpdateOccupiedRoomRequest $request, $id)
    {
        return $this->update->execute($request, $id);
    }


    protected function delete(DeleteOccupiedRoomRequest $request, $id)
    {
        return $this->delete->execute($id);
    }
}
