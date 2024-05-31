<?php

namespace App\Http\Requests\Room\RoomStatus;

use App\Http\Requests\ResponseRequest;

class UpdateRoomStatusRequest extends ResponseRequest
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
            'status' => 'nullable|string|in:OCCUPIED,DIRTY,READY FOR OCCUPANCY,UNALLOCATED'
        ];
    }
}
