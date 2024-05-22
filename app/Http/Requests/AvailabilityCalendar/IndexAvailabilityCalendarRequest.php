<?php

namespace App\Http\Requests\AvailabilityCalendar;

use App\Http\Requests\ResponseRequest;

class IndexAvailabilityCalendarRequest extends ResponseRequest
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
            'roomType' => 'nullable|exists:room_types,name',
            'roomNumber' => 'nullable|exists:rooms,room_number',
            'dateRange' => 'nullable',
        ];
    }
}
