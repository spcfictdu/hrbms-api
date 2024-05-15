<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// * REQUEST
use App\Http\Requests\Transaction\{Booking\IndexBookingRequest,
                                   Booking\ShowBookingRequest,
                                   Booking\EditBookingRequest,
                                   Booking\UpdateBookingRequest,
                                   CreateTransactionRequest};

// * REPOSITORY
use App\Repositories\Transaction\{Booking\IndexBookingRepository,
                                  Booking\ShowBookingRepository,
                                  Booking\EditBookingRepository,
                                  Booking\UpdateBookingRepository,
                                  CreateTransactionRepository};

class TransactionController extends Controller
{
    protected $bookIndex, $bookShow, $bookEdit, $bookUpdate, $create;

    public function __construct(
        IndexBookingRepository $bookIndex,
        ShowBookingRepository $bookShow,
        EditBookingRepository $bookEdit,
        UpdateBookingRepository $bookUpdate,
        CreateTransactionRepository $create
    ){
        $this->bookIndex = $bookIndex;
        $this->bookShow = $bookShow;
        $this->bookEdit = $bookEdit;
        $this->bookUpdate = $bookUpdate;
        $this->create = $create;
    }
    
    protected function bookIndex(IndexBookingRequest $request)
    {
        return $this->bookIndex->execute();
    }

    protected function bookShow($referenceNumber)
    {
        return $this->bookShow->execute($referenceNumber);
    }

    protected function bookEdit($referenceNumber){
        return $this->bookEdit->execute($referenceNumber);
    }

    protected function bookUpdate(UpdateBookingRequest $request){
        return $this->bookUpdate->execute($request);
    }

    protected function create(CreateTransactionRequest $request)
    {
        return $this->create->execute($request);
    }
}
