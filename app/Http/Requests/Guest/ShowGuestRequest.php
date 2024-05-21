<?php

namespace App\Http\Requests\Guest;

use App\Http\Requests\ResponseRequest;

class ShowGuestRequest extends ResponseRequest
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
            'referenceNumber' => 'string|nullable',
            'checkInDate' => 'date|nullable',
            'checkOutDate' => 'date|nullable'
        ];
    }
}
