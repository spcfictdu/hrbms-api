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
    ShowGuestTransactionRequest,
    UpdateTransactionRequest
};
use App\Models\Guest\Guest;
// * REPOSITORY
use App\Repositories\Transaction\{
    // Booking\IndexBookingRepository,
    // Booking\ShowBookingRepository,
    // Booking\EditBookingRepository,
    // Booking\UpdateBookingRepository,
    IndexTransactionRepository,
    CreateTransactionRepository,
    ShowTransactionRepository,
    Guest\ShowGuestTransactionRepository,
    Guest\CreateGuestTransactionRepository,
    UpdateTransactionRepository,
    Miscellaneous\DeleteReservationRepository,
    Miscellaneous\ShowFormTransactionRepository
};

class TransactionController extends Controller
{
    // $bookEdit, $bookIndex, $bookShow, $bookUpdate
    protected $index, $create, $show, $update, $deleteReservation, $showFormTransaction, $guestTransactionShow, $guestTransactionCreate;

    public function __construct(
        // IndexBookingRepository $bookIndex,
        // ShowBookingRepository $bookShow,
        // EditBookingRepository $bookEdit,
        // UpdateBookingRepository $bookUpdate,
        IndexTransactionRepository $index,
        CreateTransactionRepository $create,
        CreateGuestTransactionRepository $guestTransactionCreate,
        ShowTransactionRepository $show,
        ShowGuestTransactionRepository $guestTransactionShow,
        UpdateTransactionRepository $update,
        DeleteReservationRepository $deleteReservation,
        ShowFormTransactionRepository $showFormTransaction
    ) {
        // $this->bookIndex = $bookIndex;
        // $this->bookShow = $bookShow;
        // $this->bookEdit = $bookEdit;
        // $this->bookUpdate = $bookUpdate;
        $this->index = $index;
        $this->create = $create;
        $this->guestTransactionCreate = $guestTransactionCreate;
        $this->show = $show;
        $this->guestTransactionShow = $guestTransactionShow;
        $this->update = $update;
        $this->deleteReservation = $deleteReservation;
        $this->showFormTransaction = $showFormTransaction;
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

    public function guestTransactionIndex()
    {
        return $this->index->execute();
    }

    public function guestTransactionShow($referenceNumber)
    {
        return $this->guestTransactionShow->execute($referenceNumber);
    }

    public function guestTransactionCreate()
    {
        return $this->guestTransactionCreate->execute();
    }

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

    protected function update(UpdateTransactionRequest $request)
    {
        return $this->update->execute($request);
    }

    protected function deleteReservation($status, $referenceNumber)
    {
        return $this->deleteReservation->execute($status, $referenceNumber);
    }

    protected function showFormTransaction($referenceNumber)
    {
        return $this->showFormTransaction->execute($referenceNumber);
    }
}
