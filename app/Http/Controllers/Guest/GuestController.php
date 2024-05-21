<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

use App\Http\Requests\Guest\{IndexGuestRequest, CreateGuestRequest, ShowGuestRequest, UpdateGuestRequest, DeleteGuestRequest};

use App\Repositories\Guest\{IndexGuestRepository, CreateGuestRepository, ShowGuestRepository, UpdateGuestRepository, DeleteGuestRepository};


class GuestController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        IndexGuestRepository   $index,
        CreateGuestRepository  $create,
        ShowGuestRepository    $show,
        UpdateGuestRepository  $update,
        DeleteGuestRepository  $delete
    ) {
        $this->index   = $index;
        $this->create  = $create;
        $this->show    = $show;
        $this->update  = $update;
        $this->delete  = $delete;
    }

    protected function index(IndexGuestRequest $request)
    {
        return $this->index->execute($request);
    }


    protected function create(CreateGuestRequest $request)
    {
        return $this->create->execute($request);
    }


    protected function show(ShowGuestRequest $request, $id)
    {
        return $this->show->execute($request, $id);
    }


    protected function update(UpdateGuestRequest $request, $id)
    {
        return $this->update->execute($request, $id);
    }


    protected function delete(DeleteGuestRequest $request, $id)
    {
        return $this->delete->execute($id);
    }
}
