<?php

namespace App\Http\Requests\Voucher;

use Illuminate\Validation\Rule;
use App\Models\Discount\Voucher;
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
        $voucher = Voucher::where('reference_number', $this->referenceNumber)->firstOrFail();
        return [
            'code' => ['string', Rule::unique('vouchers', 'code')->ignore($voucher->id)],
            'value' => ['numeric'],
            'usage' => ['numeric'],
            'status' => ['string', Rule::in(['ACTIVE', 'INACTIVE'])],
            'expires_at' => ['date', 'after:today']
        ]; 
    }
}