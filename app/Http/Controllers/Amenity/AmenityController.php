<?php

namespace App\Http\Controllers\Amenity;

use App\Http\Controllers\Controller;

// * REQUEST
use App\Http\Requests\Amenity\{
    IndexAmenityRequest,
    CreateAmenityRequest,
    ShowAmenityRequest,
    UpdateAmenityRequest,
    DeleteAmenityRequest
};

// * REPOSITORY
use App\Repositories\Amenity\{
    IndexAmenityRepository,
    CreateAmenityRepository,
    ShowAmenityRepository,
    UpdateAmenityRepository,
    DeleteAmenityRepository
};

class AmenityController extends Controller
{
    protected $index, $create, $show, $update, $delete;

    public function __construct(
        IndexAmenityRepository $index,
        CreateAmenityRepository $create,
        ShowAmenityRepository $show,
        UpdateAmenityRepository $update,
        DeleteAmenityRepository $delete
    ) {
        $this->index = $index;
        $this->create = $create;
        $this->show = $show;
        $this->update = $update;
        $this->delete = $delete;
    }

    protected function index(IndexAmenityRequest $request)
    {
        return $this->index->execute();
    }

    protected function create(CreateAmenityRequest $request)
    {
        return $this->create->execute($request);
    }

    protected function show(ShowAmenityRequest $request, $referenceNumber)
    {
        return $this->show->execute($referenceNumber);
    }

    protected function update(UpdateAmenityRequest $request, $referenceNumber)
    {
        return $this->update->execute($request, $referenceNumber);
    }

    protected function delete(DeleteAmenityRequest $request, $referenceNumber)
    {
        return $this->delete->execute($referenceNumber);
    }
}
