<?php

namespace App\Http\Requests\Enum;

use App\Http\Requests\ResponseRequest;

class RoomEnumRequest extends ResponseRequest
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
            'roomType' => 'nullable|string|exists:room_types,name',
            'roomNumber' => 'nullable|integer|exists:rooms,room_number',
            'dateRange' => ['nullable', 'string', 'regex:/^\d{4}-\d{2}-\d{2},\d{4}-\d{2}-\d{2}$/'],
            'extraPersonCount' => 'nullable|integer|min:0',
            'discount' => 'nullable|string|exists:discounts,name',
            'voucherCode' => [
                'nullable',
                'string',
                'exists:vouchers,code',
                'required_if:discount,VOUCHER'
            ],
            'addons' => 'nullable|string'
        ];
    }
}
