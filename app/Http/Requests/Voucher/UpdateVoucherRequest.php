<?php

namespace App\Http\Requests\Voucher;

use Illuminate\Validation\Rule;
use App\Http\Requests\ResponseRequest;

class UpdateVoucherRequest extends ResponseRequest
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
            'code' => ['string', Rule::unique('vouchers', 'code')],
            'value' => ['numeric'],
            'usage' => ['numeric'],
            'status' => ['string', 'exists:voucer,status'],
        ];
    }
}