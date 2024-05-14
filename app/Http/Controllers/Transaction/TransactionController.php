<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// * REQUEST
use App\Http\Requests\Transaction\Booking\{CreateBookingRequest,
                               ShowBookingRequest};

// * REPOSITORY
use App\Repositories\Transaction\Booking\{CreateBookingRepository,
                              ShowBookingRepository};

class TransactionController extends Controller
{
    protected $show, $create;

    public function __construct(
        ShowBookingRepository $show,
        CreateBookingRepository $create
    ){
        $this->show = $show;
        $this->create = $create;
    }

    protected function show($referenceNumber)
    {
        return $this->show->execute($referenceNumber);
    }

    protected function create(CreateBookingRequest $request)
    {
        return $this->create->execute($request);
    }
}
