<?php

namespace App\Http\Requests\Transaction;

use App\Rules\RoomAvailable;
use Illuminate\Validation\Rule;
use App\Http\Requests\ResponseRequest;

class CreateTransactionRequest extends ResponseRequest
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
            'room.referenceNumber' => ['required', 'string', 'exists:rooms,reference_number', new RoomAvailable($this->checkIn['time'], $this->checkOut['time'], $this->checkIn['date'])],

            'guest.firstName' => ['required', 'string'],
            'guest.middleName' => ['nullable', 'string'],
            'guest.lastName' => ['required', 'string'],
            'guest.address.province' => ['required', 'string'],
            'guest.address.city' => ['required', 'string'],
            'guest.contact.phoneNum' => ['required', 'string'],
            'guest.contact.email' => ['required', 'string'],
            'guest.dbId' => ['nullable', 'integer'],
            'guest.id.type' => ['required'],
            'guest.id.number' => ['required'],

            'addons' => ['array'],
            'addons.name' => ['string', 'exists:addons, name'],
            'addons.*.quantity' => ['required_with:addons', 'string', 'min:1'],

            'discount' => ['nullable', 'string', 'exists:discounts,name'],
            'voucherCode' => ['required_if:discount,VOUCHER', 'string', 'exists:vouchers,code'],
            'idNumber' => ['required_if:discount,SNR,PWD', 'string'],

            'payment.paymentType' => ['nullable', 'string', Rule::in(['CASH', 'GCASH', 'CHEQUE', 'CREDIT_CARD'])],
            'payment.chequeNumber' => ['required_if:payment.paymentType,CHEQUE', 'string', 'unique:cheque_payments,cheque_number'],
            'payment.chequeBankName' => ['required_if:payment.paymentType,CHEQUE', 'string'],

            'payment.cardHolderName' => ['required_if:payment.paymentType,CREDIT_CARD', 'string'],
            'payment.cardNumber' => ['required_if:payment.paymentType,CREDIT_CARD', 'string', 'digits:16'],
            'payment.expirationDate' => ['required_if:payment.paymentType,CREDIT_CARD', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'], // Format: MM/YY
            'payment.cvc' => ['required_if:payment.paymentType,CREDIT_CARD', 'string', 'digits:3'],

            'payment.bankId' => ['required_if:payment.paymentType,CREDIT_CARD', 'required_if:payment.paymentType,CHEQUE'],

            'roomTotal' => ['required'],
            // 'guest.numberOfGuest' => ['required', 'integer'],
            'checkIn.date' => ['required', 'date', 'after_or_equal:today'],
            'checkIn.time' => ['date_format:H:i'],
            'checkOut.date' => ['required', 'date', 'after:checkIn.date'],
            'checkOut.time' => ['date_format:H:i']
        ];
    }
}
