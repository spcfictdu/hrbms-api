<?php

namespace App\Http\Controllers\AvailabilityCalendar;

use App\Http\Controllers\Controller;

use App\Http\Requests\AvailabilityCalendar\IndexAvailabilityCalendarRequest;
use App\Http\Requests\AvailabilityCalendar\CreateAvailabilityCalendarRequest;
use App\Http\Requests\AvailabilityCalendar\ShowAvailabilityCalendarRequest;
use App\Http\Requests\AvailabilityCalendar\UpdateAvailabilityCalendarRequest;
use App\Http\Requests\AvailabilityCalendar\DeleteAvailabilityCalendarRequest;
use App\Models\Room\Room;
use App\Models\Room\RoomType;
use App\Repositories\AvailabilityCalendar\IndexAvailabilityCalendarRepository;
use App\Repositories\AvailabilityCalendar\CreateAvailabilityCalendarRepository;
use App\Repositories\AvailabilityCalendar\ShowAvailabilityCalendarRepository;
use App\Repositories\AvailabilityCalendar\UpdateAvailabilityCalendarRepository;
use App\Repositories\AvailabilityCalendar\DeleteAvailabilityCalendarRepository;
use Illuminate\Http\Request;

class AvailabilityCalendarController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    // * CONSTRUCTOR INJECTION
    public function __construct(
        IndexAvailabilityCalendarRepository   $index,
        CreateAvailabilityCalendarRepository  $create,
        ShowAvailabilityCalendarRepository    $show,
        UpdateAvailabilityCalendarRepository  $update,
        DeleteAvailabilityCalendarRepository  $delete
    ) {
        $this->index   = $index;
        $this->create  = $create;
        $this->show    = $show;
        $this->update  = $update;
        $this->delete  = $delete;
    }

    protected function index(IndexAvailabilityCalendarRequest $request)
    {
        return $this->index->execute($request);
    }


    protected function create(CreateAvailabilityCalendarRequest $request)
    {
        return $this->create->execute($request);
    }


    protected function show(ShowAvailabilityCalendarRequest $request, $id)
    {
        return $this->show->execute($id);
    }


    protected function update(UpdateAvailabilityCalendarRequest $request, $id)
    {
        return $this->update->execute($request, $id);
    }


    protected function delete(DeleteAvailabilityCalendarRequest $request, $id)
    {
        return $this->delete->execute($id);
    }
}
