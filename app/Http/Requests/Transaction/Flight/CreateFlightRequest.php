<?php

namespace App\Http\Requests\Transaction\Flight;

use App\Http\Requests\ResponseRequest;

class CreateFlightRequest extends ResponseRequest
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
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'flightGroup' => 'sometimes',
            'arrivalFlightNumber' => 'sometimes|string',
            'departureFlightNumber' => 'sometimes|string',
            'departureDate' => ['required_with:departureFlightNumber', 'date'],
            'departureTime' => ['required_with:departureDate', 'date_format:H:i'],
            'arrivalDate' => ['required_with:arrivalFlightNumber', 'date'],
            'arrivalTime' => ['required_with:arrivalDate', 'date_format:H:i'],
        ];
    }
}