<?php

namespace App\Http\Requests\Room\RoomStatus;

use App\Http\Requests\ResponseRequest;

class IndexRoomStatusRequest extends ResponseRequest
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
            'page' => 'nullable|integer',
            'perPage' => 'nullable|integer',
            'sortBy' => 'nullable|string',
            'sortOrder' => 'nullable|string',
            'roomType' => 'nullable|string',
            'roomStatus' => 'nullable|in:AVAILABLE,OCCUPIED,UNCLEAN,UNALLOCATED'
        ];
    }
}
