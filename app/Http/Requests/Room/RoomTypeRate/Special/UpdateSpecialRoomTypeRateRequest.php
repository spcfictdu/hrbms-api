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
            'rates.monday' => ['required', 'numeric', 'min:0'],
            'rates.tuesday' => ['required', 'numeric', 'min:0'],
            'rates.wednesday' => ['required', 'numeric', 'min:0'],
            'rates.thursday' => ['required', 'numeric', 'min:0'],
            'rates.friday' => ['required', 'numeric', 'min:0'],
            'rates.saturday' => ['required', 'numeric', 'min:0'],
            'rates.sunday' => ['required', 'numeric', 'min:0'],
        ];
    }
}
