<?php

namespace App\Repositories\Transaction\Guest;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\{Arr, Str};

use Carbon\Carbon;


class ShowGuestTransactionRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $transaction = Guest::where('reference_number', $referenceNumber)->first();

        if (!$transaction) {
            return $this->error('Transaction not found.', [], 404);
        }

        $data = [
            // 'guest' => $transaction->guest,
            'guest' => [
                'id' => $transaction->id,
                'referenceNumber' => $transaction->reference_number,
                'fullName' => $transaction->full_name,
                'firstName' => $transaction->first_name,
                'middleName' => $transaction->middle_name,
                'lastName' => $transaction->last_name,
                'address' => [
                    'province' => $transaction->province,
                    'city' => $transaction->city
                ],
                'contact' => [
                    'phoneNum' => $transaction->phone_number,
                    'email' => $transaction->email
                ],
                'id' => [
                    'type' => $transaction->id_type,
                    'number' => $transaction->id_number
                ]
            ],
        ];

        return $this->success('Guest transaction retrieved successfully.', $data);
    }
}
