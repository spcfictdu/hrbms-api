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
    UpdateTransactionRequest,
    Flight\CreateFlightRequest,
    Flight\UpdateFlightRequest,
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
    Guest\IndexGuestRepository,
    Guest\ShowGuestTransactionRepository,
    Guest\CreateGuestTransactionRepository,
    UpdateTransactionRepository,
    Miscellaneous\DeleteReservationRepository,
    Miscellaneous\ShowFormTransactionRepository,
    Flight\CreateFlightRepository,
    Flight\IndexFlightRepository,
    Flight\ShowFlightRepository,
    Flight\UpdateFlightRepository,
    Flight\DeleteFlightRepository,
};

class TransactionController extends Controller
{
    // $bookEdit, $bookIndex, $bookShow, $bookUpdate
    protected $index, $create, $show, $update, $deleteReservation, $showFormTransaction, $guestIndex, $guestTransactionShow, $guestTransactionCreate, $indexFlight, $createFlight, $showFlight, $updateFlight, $deleteFlight;

    public function __construct(
        // IndexBookingRepository $bookIndex,
        // ShowBookingRepository $bookShow,
        // EditBookingRepository $bookEdit,
        // UpdateBookingRepository $bookUpdate,
        IndexTransactionRepository $index,
        IndexGuestRepository $guestIndex,
        CreateTransactionRepository $create,
        CreateGuestTransactionRepository $guestTransactionCreate,
        ShowTransactionRepository $show,
        ShowGuestTransactionRepository $guestTransactionShow,
        UpdateTransactionRepository $update,
        DeleteReservationRepository $deleteReservation,
        ShowFormTransactionRepository $showFormTransaction,
        IndexFlightRepository $indexFlight,
        CreateFlightRepository $createFlight,
        ShowFlightRepository $showFlight,
        UpdateFlightRepository $updateFlight,
        DeleteFlightRepository $deleteFlight,
    ) {
        // $this->bookIndex = $bookIndex;
        // $this->bookShow = $bookShow;
        // $this->bookEdit = $bookEdit;
        // $this->bookUpdate = $bookUpdate;
        $this->index = $index;
        $this->guestIndex = $guestIndex;
        $this->create = $create;
        $this->guestTransactionCreate = $guestTransactionCreate;
        $this->show = $show;
        $this->guestTransactionShow = $guestTransactionShow;
        $this->update = $update;
        $this->deleteReservation = $deleteReservation;
        $this->showFormTransaction = $showFormTransaction;
        $this->indexFlight = $indexFlight;
        $this->createFlight = $createFlight;
        $this->showFlight = $showFlight;
        $this->updateFlight = $updateFlight;
        $this->deleteFlight = $deleteFlight;
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

    public function guestIndex()
    {
        return $this->guestIndex->execute();
    }

    public function guestTransactionShow($referenceNumber)
    {
        return $this->guestTransactionShow->execute($referenceNumber);
    }

    public function guestTransactionCreate(Request $request)
    {
        return $this->guestTransactionCreate->execute($request);
    }

    protected function index(IndexTransactionRequest $request)
    {
        return $this->index->execute($request);
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

    protected function indexFlight($referenceNumber)
    {
        return $this->indexFlight->execute($referenceNumber);
    }

    protected function createFlight(CreateFlightRequest $request, $referenceNumber)
    {
        return $this->createFlight->execute($request, $referenceNumber);
    }

    protected function showFlight(Request $request)
    {
        return $this->showFlight->execute($request);
    }

    protected function updateFlight(UpdateFlightRequest $request)
    {
        return $this->updateFlight->execute($request);
    }

    protected function deleteFlight(Request $request)
    {
        return $this->deleteFlight->execute($request);
    }
}
