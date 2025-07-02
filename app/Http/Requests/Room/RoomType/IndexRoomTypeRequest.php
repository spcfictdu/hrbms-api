<?php

namespace App\Http\Requests\Room\RoomType;

use App\Http\Requests\ResponseRequest;

class IndexRoomTypeRequest extends ResponseRequest
{
     /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('ADMIN') || $this->user()->hasRole('FRONT DESK');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
