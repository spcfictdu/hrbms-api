<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

use App\Http\Requests\Guest\{IndexGuestRequest, CreateGuestRequest, ShowGuestRequest, UpdateGuestRequest, DeleteGuestRequest};

use App\Http\Requests\Guest\Miscellaneous\{AccountInfoRequest, EditAccountInfoRequest, UpdateAccountInfoRequest, UpdateAccountPasswordRequest};

use App\Repositories\Guest\{IndexGuestRepository, CreateGuestRepository, ShowGuestRepository, UpdateGuestRepository, DeleteGuestRepository};

use App\Repositories\Guest\Miscellaneous\{AccountInfoRepository, EditAccountInfoRepository, UpdateAccountInfoRepository, UpdateAccountPasswordRepository};


class GuestController extends Controller
{
    protected $index, $create, $show, $update, $delete, $accountDetails, $editDetails, $updateDetails, $changePassword;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        IndexGuestRepository            $index,
        CreateGuestRepository           $create,
        ShowGuestRepository             $show,
        UpdateGuestRepository           $update,
        DeleteGuestRepository           $delete,
        AccountInfoRepository           $accountDetails,
        EditAccountInfoRepository       $editDetails,
        UpdateAccountInfoRepository     $updateDetails,
        UpdateAccountPasswordRepository $changePassword
    ) {
        $this->index            = $index;
        $this->create           = $create;
        $this->show             = $show;
        $this->update           = $update;
        $this->delete           = $delete;
        $this->accountDetails   = $accountDetails;
        $this->editDetails      = $editDetails;
        $this->updateDetails    = $updateDetails;
        $this->changePassword   = $changePassword;
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

    protected function accountDetails()
    {
        return $this->accountDetails->execute();
    }

    protected function editDetails(EditAccountInfoRequest $request)
    {
        return $this->editDetails->execute();
    }

    protected function updateDetails(UpdateAccountInfoRequest $request)
    {
        return $this->updateDetails->execute($request);
    }

    protected function changePassword(UpdateAccountPasswordRequest $request)
    {
        return $this->changePassword->execute($request);
    }
}
