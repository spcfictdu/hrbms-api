<?php

namespace App\Http\Requests\Room\Room;

use App\Http\Requests\ResponseRequest;

class CreateRoomRequest extends ResponseRequest
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
            'roomNumber' => ['required', 'integer', 'unique:rooms,room_number'],
            'roomType' => ['required', 'exists:room_types,name']
        ];
    }
}
