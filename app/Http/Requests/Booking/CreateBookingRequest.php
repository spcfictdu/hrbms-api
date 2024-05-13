<?php

namespace App\Http\Requests\Booking;

use App\Http\Requests\ResponseRequest;

class CreateBookingRequest extends ResponseRequest
{
     /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('ADMIN');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'guest.firstName' => ['required', 'string'],
            'guest.middleName' => ['required', 'string'],
            'guest.lastName' => ['required', 'string'],
            'guest.address.province' => ['required', 'string'],
            'guest.address.city' => ['required', 'string'],
            'guest.contact.phoneNum' => ['required', 'string'],
            'guest.contact.email' => ['required', 'string'],
            'guest.id.type' => ['required'],
            'guest.id.number' => ['required'],
            'guest.numberOfGuest' => ['required', 'integer'],
            'checkIn.date' => ['required', 'date'],
            'checkIn.time' => ['date_format:H:i'],
            'checkOut.date' => ['required', 'date'],
            'checkOut.time' => ['date_format:H:i']
        ];
    }
}