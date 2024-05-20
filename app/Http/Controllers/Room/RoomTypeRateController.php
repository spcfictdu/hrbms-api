<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;

// * REQUEST
use App\Http\Requests\Room\RoomTypeRate\{
    IndexRoomTypeRateRequest,
    ShowRoomTypeRateRequest,
    Regular\UpdateRegularRoomTypeRateRequest,
    Special\CreateSpecialRoomTypeRateRequest,
    Special\UpdateSpecialRoomTypeRateRequest,
    Special\ArchivedSpecialRoomTypeRateRequest,
    Special\ArchiveSpecialRoomTypeRateRequest,
    Special\RestoreSpecialRoomTypeRateRequest
};

// * REPOSITORY
use App\Repositories\Room\RoomTypeRate\{
    IndexRoomTypeRateRepository,
    ShowRoomTypeRateRepository,
    Regular\UpdateRegularRoomTypeRateRepository,
    Special\CreateSpecialRoomTypeRateRepository,
    Special\UpdateSpecialRoomTypeRateRepository,
    Special\ArchivedSpecialRoomTypeRateRepository,
    Special\ArchiveSpecialRoomTypeRateRepository,
    Special\RestoreSpecialRoomTypeRateRepository
};

class RoomTypeRateController extends Controller
{
    protected $index, $show, $updateRegular, $createSpecial, $updateSpecial, $archivedSpecial, $archiveSpecial, $restoreSpecial;

    public function __construct(
        IndexRoomTypeRateRepository $index,
        ShowRoomTypeRateRepository $show,
        UpdateRegularRoomTypeRateRepository $updateRegular,
        CreateSpecialRoomTypeRateRepository $createSpecial,
        UpdateSpecialRoomTypeRateRepository $updateSpecial,
        ArchivedSpecialRoomTypeRateRepository $archivedSpecial,
        ArchiveSpecialRoomTypeRateRepository $archiveSpecial,
        RestoreSpecialRoomTypeRateRepository $restoreSpecial
    ) {
        $this->index = $index;
        $this->show = $show;
        $this->updateRegular = $updateRegular;
        $this->createSpecial = $createSpecial;
        $this->updateSpecial = $updateSpecial;
        $this->archivedSpecial = $archivedSpecial;
        $this->archiveSpecial = $archiveSpecial;
        $this->restoreSpecial = $restoreSpecial;
    }

    protected function index(IndexRoomTypeRateRequest $request)
    {
        return $this->index->execute();
    }
    protected function show(ShowRoomTypeRateRequest $request, $roomReferenceNumber)
    {
        return $this->show->execute($roomReferenceNumber);
    }

    protected function updateRegular(UpdateRegularRoomTypeRateRequest $request, $referenceNumber)
    {
        return $this->updateRegular->execute($request, $referenceNumber);
    }

    protected function createSpecial(CreateSpecialRoomTypeRateRequest $request)
    {
        return $this->createSpecial->execute($request);
    }

    protected function updateSpecial(UpdateSpecialRoomTypeRateRequest $request, $referenceNumber)
    {
        return $this->updateSpecial->execute($request, $referenceNumber);
    }

    protected function archivedSpecial(ArchivedSpecialRoomTypeRateRequest $request, $roomTypeReferenceNumber)
    {
        return $this->archivedSpecial->execute($roomTypeReferenceNumber);
    }

    protected function archiveSpecial(ArchiveSpecialRoomTypeRateRequest $request, $referenceNumber)
    {
        return $this->archiveSpecial->execute($referenceNumber);
    }

    protected function restoreSpecial(RestoreSpecialRoomTypeRateRequest $request, $referenceNumber)
    {
        return $this->restoreSpecial->execute($referenceNumber);
    }
}
