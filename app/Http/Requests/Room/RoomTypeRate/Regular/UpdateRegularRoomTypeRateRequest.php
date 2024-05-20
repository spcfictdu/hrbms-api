<?php

namespace App\Http\Requests\Room\RoomTypeRate\Regular;

use App\Http\Requests\ResponseRequest;

class UpdateRegularRoomTypeRateRequest extends ResponseRequest
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
