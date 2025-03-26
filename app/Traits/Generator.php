<?php

namespace App\Traits;

use App\Models\{
    Room\RoomType,
    Amenity\Amenity,
    Room\RoomTypeRate,
    Room\Room,
    Transaction\Transaction,
    Amenity\Addon,
    Discount\Voucher
};
use App\Models\Guest\Guest;

trait Generator
{
    protected function roomTypeReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(4));
        } while (RoomType::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }

    protected function amenityReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(3));
        } while (Amenity::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }

    protected function addonReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(3));
        } while (Addon::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }
    protected function voucherReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(3));
        } while (Voucher::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }

    protected function ratesReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(4));
        } while (RoomTypeRate::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }

    protected function roomReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(4));
        } while (Room::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }

    protected function transactionReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(2)) . '-' . bin2hex(random_bytes(2)) . '-' . bin2hex(random_bytes(2));
        } while (Transaction::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }

    protected function guestReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(4));
        } while (Guest::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }

    // New method, not being used yet    
    // private function generateReferenceNumber()
    // {
    //     // Your logic to generate a reference number
    //     return 'TRX' . uniqid();
    // }
}
