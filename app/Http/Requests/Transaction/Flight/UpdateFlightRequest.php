<?php

namespace App\Http\Requests\Transaction\Flight;

use App\Http\Requests\ResponseRequest;

class UpdateFlightRequest extends ResponseRequest
{
     /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'flightId' => 'required',
            'flightNumber' => 'sometimes|string',
            'firstName' => 'sometimes|string',
            'lastName' => 'sometimes|string',
            'departureDate' => 'sometimes|date',
            'departureTime' => ['sometimes', 'date_format:H:i'],
            'arrivalDate' => 'sometimes|date',
            'arrivalTime' => ['sometimes', 'date_format:H:i'],
        ];
    }
}