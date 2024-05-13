<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// * REQUEST
use App\Http\Requests\Booking\{CreateBookingRequest};

// * REPOSITORY
use App\Repositories\Booking\{CreateBookingRepository};

class BookingController extends Controller
{
    protected $create;

    public function __construct(
        CreateBookingRepository $create
    ){
        $this->create = $create;
    }

    protected function create(CreateBookingRequest $request)
    {
        return $this->create->execute($request);
    }
}
