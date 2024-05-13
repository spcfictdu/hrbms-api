<?php

namespace App\Http\Requests\Room\Room;

use App\Http\Requests\ResponseRequest;

use Illuminate\Validation\Rule;

use App\Models\Room\Room;

class UpdateRoomRequest extends ResponseRequest
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

        $room = Room::where('reference_number', $this->referenceNumber)->firstOrFail();

        return [
            'roomNumber' => ['required', 'integer', Rule::unique('rooms', 'room_number')->ignore($room->id)],
            'roomType' => ['required', 'exists:room_types,name']
        ];
    }
}
