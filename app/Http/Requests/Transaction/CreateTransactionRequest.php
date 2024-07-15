<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\ResponseRequest;
use App\Rules\RoomAvailable;

class CreateTransactionRequest extends ResponseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('ADMIN') || $this->user()->hasRole('FRONT DESK') || $this->user()->hasRole('GUEST');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'room.referenceNumber' => ['required', 'string', 'exists:rooms,reference_number', new RoomAvailable($this->checkIn['date'], $this->checkOut['date'])],

            'guest.firstName' => ['required', 'string'],
            'guest.middleName' => ['nullable', 'string'],
            'guest.lastName' => ['required', 'string'],
            'guest.address.province' => ['required', 'string'],
            'guest.address.city' => ['required', 'string'],
            'guest.contact.phoneNum' => ['required', 'string'],
            'guest.contact.email' => ['required', 'string'],
            'guest.id.type' => ['required'],
            'guest.id.number' => ['required'],
            // 'guest.numberOfGuest' => ['required', 'integer'],
            'checkIn.date' => ['required', 'date', 'after_or_equal:today'],
            'checkIn.time' => ['date_format:H:i'],
            'checkOut.date' => ['required', 'date', 'after:checkIn.date'],
            'checkOut.time' => ['date_format:H:i']
        ];
    }
}
