<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

use App\Http\Requests\Guest\{IndexGuestRequest, CreateGuestRequest, ShowGuestRequest, UpdateGuestRequest, DeleteGuestRequest};

use App\Http\Requests\Guest\Miscellaneous\AccountInfoRequest;

use App\Repositories\Guest\{IndexGuestRepository, CreateGuestRepository, ShowGuestRepository, UpdateGuestRepository, DeleteGuestRepository};

use App\Repositories\Guest\Miscellaneous\AccountInfoRepository;


class GuestController extends Controller
{
    protected $index, $create, $show, $update, $delete, $accountDetails;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        IndexGuestRepository   $index,
        CreateGuestRepository  $create,
        ShowGuestRepository    $show,
        UpdateGuestRepository  $update,
        DeleteGuestRepository  $delete,
        AccountInfoRepository  $accountDetails
    ) {
        $this->index   = $index;
        $this->create  = $create;
        $this->show    = $show;
        $this->update  = $update;
        $this->delete  = $delete;
        $this->accountDetails = $accountDetails;
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

    protected function accountDetails(){
        return $this->accountDetails->execute();
    }
}
