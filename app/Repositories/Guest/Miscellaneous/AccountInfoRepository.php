<?php

namespace App\Repositories\Guest\Miscellaneous;

use App\Models\Transaction\Transaction;
use App\Repositories\BaseRepository;

use App\Models\Guest\Guest;

class AccountInfoRepository extends BaseRepository
{
    public function execute()
    {
        // return $this->user();
        if ($this->user()->getRoleNames()->first() == "GUEST") {
            $guest = Guest::where('user_id', $this->user()->id)->first();

            $bookings = Transaction::where('guest_id', $guest->id)->where('status', 'CONFIRMED')->get()->map(function ($booking) {
                return [
                    'referenceNumber' => $booking->reference_number,
                    'status' => $booking->status,
                    'roomName' => $booking->room->roomType->name,
                    'checkInDate' => $booking->check_in_date,
                    'checkOutDate' => $booking->check_out_date,
                    'amountReceived' => $booking->payment->amount_received,
                    'roomDescription' => $booking->room->roomType->description,
                    'amenities' => $booking->room->roomType->amenities->map(function ($amenity) {
                        return [
                            'name' => $amenity->name,
                            'quantity' => $amenity->pivot->quantity
                        ];
                    })
                ];
            });

            $reservations = Transaction::where('guest_id', $guest->id)->where('status', 'RESERVED')->get()->map(function ($reservation) {
                return [
                    'referenceNumber' => $reservation->reference_number,
                    'status' => $reservation->status,
                    'roomName' => $reservation->room->roomType->name,
                    'checkInDate' => $reservation->check_in_date,
                    'checkOutDate' => $reservation->check_out_date,
                    'amountReceived' => $reservation->payment?->amount_received,
                    'roomDescription' => $reservation->room->roomType->description,
                    'amenities' => $reservation->room->roomType->amenities->map(function ($amenity) {
                        return [
                            'name' => $amenity->name,
                            'quantity' => $amenity->pivot->quantity
                        ];
                    })
                ];
            });

            $histories = Transaction::where('guest_id', $guest->id)->where('status', 'CHECKED-OUT')->get()->map(function ($history) {
                return [
                    'referenceNumber' => $history->reference_number,
                    'status' => $history->status,
                    'roomName' => $history->room->roomType->name,
                    'checkInDate' => $history->check_in_date,
                    'checkOutDate' => $history->check_out_date,
                    'amountReceived' => $history->payment->amount_received,
                    'roomDescription' => $history->room->roomType->description,
                    'amenities' => $history->room->roomType->amenities->map(function ($amenity) {
                        return [
                            'name' => $amenity->name,
                            'quantity' => $amenity->pivot->quantity
                        ];
                    })
                ];
            });

            return $this->success("Acoount Information and Previous Transaction Histories", [
                "accountInfo" => [
                    'fullName' => $guest->full_name,
                    'firstName' => $guest->first_name,
                    'middleName' => $guest->middle_name ?? null,
                    'lastName' => $guest->last_name,
                    'address' => [
                        'province' => $guest->province,
                        'city' => $guest->city
                    ],
                    'email' => $guest->email,
                    'phone' => $guest->phone_number
                ],
                'bookings' => $bookings,
                'reservation' => $reservations,
                'histories' => $histories

            ]);
        } else {
            return $this->error("Guest not found.");
        }
    }
}
