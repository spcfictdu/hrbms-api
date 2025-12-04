<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\ResponseRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends ResponseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('ADMIN') || $this->user()->hasRole('GUEST') || $this->user()->hasRole('FRONT DESK'); ; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'addons' => ['array'],
            'addons.name' => ['string', 'exists:addons, name'],
            'addons.*.quantity' => ['required_with:addons', 'string', 'min:1'],

            'discount' => ['nullable', 'string', 'exists:discounts,name'],
            'voucherCode' => ['required_if:discount,VOUCHER', 'string', 'exists:vouchers,code'],
            'idNumber' => ['required_ if:discount,SNR,PWD', 'string'],

            'paymentType' => ['string', Rule::in(['CASH', 'GCASH', 'CHEQUE', 'CREDIT_CARD'])],
            'chequeNumber' => ['required_if:paymentType,CHEQUE', 'string', 'unique:cheque_payments,cheque_number'],
            'chequeBankName' => ['required_if:paymentType,CHEQUE', 'string'],

            'cardHolderName' => ['required_if:paymentType,CREDIT_CARD', 'string'],
            'cardNumber' => ['required_if:paymentType,CREDIT_CARD', 'string', 'digits:16'],
            'expirationDate' => ['required_if:paymentType,CREDIT_CARD', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'], // Format: MM/YY
            'cvc' => ['required_if:paymentType,CREDIT_CARD', 'string', 'digits:3'],

            'bankId' => ['required_if:payment.paymentType,CREDIT_CARD', 'required_if:payment.paymentType,CHEQUE'],
        ];
    }
}
