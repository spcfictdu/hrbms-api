<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// * REQUEST
use App\Http\Requests\Transaction\{
                                // Booking\IndexBookingRequest,
                                // Booking\ShowBookingRequest,
                                // Booking\EditBookingRequest,
                                // Booking\UpdateBookingRequest,
                                   IndexTransactionRequest,
                                   CreateTransactionRequest,
                                   ShowTransactionRequest,
                                   UpdateTransactionRequest};

// * REPOSITORY
use App\Repositories\Transaction\{
                                // Booking\IndexBookingRepository,
                                // Booking\ShowBookingRepository,
                                // Booking\EditBookingRepository,
                                // Booking\UpdateBookingRepository,
                                  IndexTransactionRepository,
                                  CreateTransactionRepository,
                                  ShowTransactionRepository,
                                  UpdateTransactionRepository,
                                  Miscellaneous\DeleteReservationRepository};

class TransactionController extends Controller
{
    // $bookEdit, $bookIndex, $bookShow, $bookUpdate
    protected $index, $create, $show, $update, $deleteReservation;

    public function __construct(
        // IndexBookingRepository $bookIndex,
        // ShowBookingRepository $bookShow,
        // EditBookingRepository $bookEdit,
        // UpdateBookingRepository $bookUpdate,
        IndexTransactionRepository $index,
        CreateTransactionRepository $create,
        ShowTransactionRepository $show,
        UpdateTransactionRepository $update,
        DeleteReservationRepository $deleteReservation
    ){
        // $this->bookIndex = $bookIndex;
        // $this->bookShow = $bookShow;
        // $this->bookEdit = $bookEdit;
        // $this->bookUpdate = $bookUpdate;
        $this->index = $index;
        $this->create = $create;
        $this->show = $show;
        $this->update = $update;
        $this->deleteReservation = $deleteReservation;
    }
    
    // protected function bookIndex(IndexBookingRequest $request)
    // {
    //     return $this->bookIndex->execute();
    // }

    // protected function bookShow($referenceNumber)
    // {
    //     return $this->bookShow->execute($referenceNumber);
    // }

    // protected function bookEdit($referenceNumber)
    // {
    //     return $this->bookEdit->execute($referenceNumber);
    // }

    // protected function bookUpdate(UpdateBookingRequest $request)
    // {
    //     return $this->bookUpdate->execute($request);
    // }

    protected function index(IndexTransactionRequest $request)
    {
        return $this->index->execute();
    }

    protected function create(CreateTransactionRequest $request)
    {
        return $this->create->execute($request);
    }

    protected function show($referenceNumber)
    {
        return $this->show->execute($referenceNumber);
    }

    protected function update(UpdateTransactionRequest $request){
        return $this->update->execute($request);
    }

    protected function deleteReservation($status, $referenceNumber){
        return $this->deleteReservation->execute($status, $referenceNumber);
    }
}
