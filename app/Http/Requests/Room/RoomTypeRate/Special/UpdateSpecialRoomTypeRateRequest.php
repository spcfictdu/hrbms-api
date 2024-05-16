<?php

namespace App\Http\Requests\Room\RoomTypeRate\Special;

use App\Http\Requests\ResponseRequest;

class UpdateSpecialRoomTypeRateRequest extends ResponseRequest
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
            'discountName' => ['required', 'string'],
            'startDate' => ['required', 'date_format:Y-m-d'],
            'endDate' => ['required', 'date_format:Y-m-d'],
            'rates' => ['array'],
            'rates.monday' => ['required', 'numeric'],
            'rates.tuesday' => ['required', 'numeric'],
            'rates.wednesday' => ['required', 'numeric'],
            'rates.thursday' => ['required', 'numeric'],
            'rates.friday' => ['required', 'numeric'],
            'rates.saturday' => ['required', 'numeric'],
            'rates.sunday' => ['required', 'numeric'],
        ];
    }
}
