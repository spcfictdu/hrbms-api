<?php

namespace App\Repositories\Transaction;

use App\Models\Room\Room;
use App\Models\Guest\Guest;
use Illuminate\Support\Arr;
use App\Models\Discount\Voucher;
use App\Mail\BookTransactionMail;
use App\Models\Discount\Discount;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction\Payment;
use App\Mail\ReserveTransactionMail;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Mail;
use App\Models\Transaction\Transaction;

class CreateTransactionRepository extends BaseRepository
{
    public function execute($request)
    {
        DB::beginTransaction();

        try {
            $room = Room::where('reference_number', $request->room['referenceNumber'])->first();

            if (!$room) {
                return $this->error('Room not found.');
            }

            if ($room->status === 'OCCUPIED') {
                return $this->error('Room is already occupied.');
            }

            $guestDetails = [
                'reference_number' => $this->guestReferenceNumber(),
                'first_name' => strtoupper($request->guest['firstName']),
                'middle_name' => strtoupper($request->guest['middleName'] ?? ''),
                'last_name' => strtoupper($request->guest['lastName']),
                'province' => strtoupper($request->guest['address']['province']),
                'city' => strtoupper($request->guest['address']['city']),
                'phone_number' => $request->guest['contact']['phoneNum'],
                'email' => $request->guest['contact']['email'],
            ];

            $guest = null;
            $guestAccountId = $request->guest['accountId'] ?? null;
            $userId = $request->user()->id ?? null;

            if ($guestAccountId) {
                $guest = Guest::find($guestAccountId);
            } elseif ($userId) {
                $guest = Guest::where('user_id', $userId)->first();
            }

            if (!$guest) {
                $guest = Guest::create($guestDetails + [
                    'id_type' => strtoupper($request->guest['id']['type']),
                    'id_number' => $request->guest['id']['number'],
                ]);
            } else {
                $guest->update($guestDetails + [
                    'id_type' => strtoupper($request->guest['id']['type']),
                    'id_number' => $request->guest['id']['number'],
                ]);
            }

            $transaction = Transaction::create([
                "reference_number" => $this->transactionReferenceNumber(),
                "room_id" => $room->id,
                "status" => strtoupper($request->status),
                "check_in_date" => $request->checkIn['date'],
                "check_in_time" => $request->checkIn['time'],
                "check_out_date" => $request->checkOut['date'],
                "check_out_time" => $request->checkOut['time'],
                "number_of_guest" => $request->guest['extraPerson'],
                "guest_id" => $guest->id,
            ]);

            //

            $payment = null;
            
            $discount = Discount::where('name', $request->input('discount'))->first();
            if($request->discount){
                $discountName = Discount::where('name', $request->discount)->first();
      
           
                if($discountName->name === 'VOUCHER'){
                    $voucher = Voucher::where('code', $request->voucherCode)->first();
    
                    
                    $discount = $voucher->value;
                   
                }
            }else{
                $discountName = null;
            }

            if (isset($request->payment) && isset($transaction->id)) {
                $payment = Payment::create([
                    "transaction_id" => $transaction->id,
                    "payment_type" => strtoupper($request->payment['paymentType']),
                    "discount_id" => optional($discountName)->id,
                    "amount_received" => $request->payment['amountReceived']
                ]);

                $room->update([
                    "status" => strtoupper("OCCUPIED")
                ]);
            }


            DB::commit();
            if (app()->environment('production')) {
                if ($guest) {
                    if ($payment) {
                        Mail::to($guest->email)->send(new BookTransactionMail($transaction));
                        return $this->success("Book Transaction Created Successfully.", Arr::collapse([
                            $this->getCamelCase($guest->toArray()),
                            $this->getCamelCase($transaction->toArray()),
                            $this->getCamelCase($payment->toArray())
                        ]));
                    } else {
                        Mail::to($guest->email)->send(new ReserveTransactionMail($transaction));
                        return $this->success("Reservation Transaction Created Successfully.", Arr::collapse([
                            $this->getCamelCase($guest->toArray()),
                            $this->getCamelCase($transaction->toArray())
                        ]));
                    }
                }
            }
            return response()->json(['message' => 'Successfully created transaction.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Something went wrong: ' . $e->getMessage());
        }
    }
}
