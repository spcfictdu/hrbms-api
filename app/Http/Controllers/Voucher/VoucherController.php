<?php

namespace App\Http\Controllers\Voucher;

use App\Http\Controllers\Controller;

use App\Http\Requests\Voucher\{IndexVoucherRequest, CreateVoucherRequest, ShowVoucherRequest, UpdateVoucherRequest, DeleteVoucherRequest};

use App\Repositories\Voucher\{IndexVoucherRepository, CreateVoucherRepository, ShowVoucherRepository, UpdateVoucherRepository, DeleteVoucherRepository};


class VoucherController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        IndexVoucherRepository   $index,
        CreateVoucherRepository  $create,
        ShowVoucherRepository    $show,
        UpdateVoucherRepository  $update,
        DeleteVoucherRepository  $delete
    ){
        $this->index   = $index;
        $this->create  = $create;
        $this->show    = $show;
        $this->update  = $update;
        $this->delete  = $delete;
    }

    protected function index(IndexVoucherRequest $request) {
        return $this->index->execute();
    }

    
    protected function create(CreateVoucherRequest $request) {
        return $this->create->execute($request);
    }

    
    protected function show(ShowVoucherRequest $request, $referenceNumber) {
        return $this->show->execute($referenceNumber);
    }

    
    protected function update(UpdateVoucherRequest $request, $referenceNumber) {
        return $this->update->execute($request, $referenceNumber);
    }


    protected function delete(DeleteVoucherRequest $request, $referenceNumber) {
        return $this->delete->execute($referenceNumber);
    }
}