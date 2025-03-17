<?php

namespace App\Http\Controllers\Addon;

use App\Http\Controllers\Controller;

use App\Http\Requests\Addon\{IndexAddonRequest, CreateAddonRequest, ShowAddonRequest, UpdateAddonRequest, DeleteAddonRequest};

use App\Repositories\Addon\{IndexAddonRepository, CreateAddonRepository, ShowAddonRepository, UpdateAddonRepository, DeleteAddonRepository};


class AddonController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        IndexAddonRepository   $index,
        CreateAddonRepository  $create,
        ShowAddonRepository    $show,
        UpdateAddonRepository  $update,
        DeleteAddonRepository  $delete
    ){
        $this->index   = $index;
        $this->create  = $create;
        $this->show    = $show;
        $this->update  = $update;
        $this->delete  = $delete;
    }

    protected function index(IndexAddonRequest $request) {
        return $this->index->execute();
    }

    
    protected function create(CreateAddonRequest $request) {
        return $this->create->execute($request);
    }

    
    protected function show(ShowAddonRequest $request, $referenceNumber) {
        return $this->show->execute($referenceNumber);
    }

    
    protected function update(UpdateAddonRequest $request, $referenceNumber) {
        return $this->update->execute($request, $referenceNumber);
    }


    protected function delete(DeleteAddonRequest $request, $id) {
        return $this->delete->execute($id);
    }
}