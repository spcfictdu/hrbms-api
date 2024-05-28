<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\ResponseRequest;

class IndexTransactionRequest extends ResponseRequest
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
            'firstName' => 'string|nullable',
            'middleName' => 'string|nullable',
            'lastName' => 'string|nullable',
            'referenceNumber' => 'string|nullable',
            'checkInDate' => 'date|nullable',
            'checkOutDate' => 'date|nullable',
        ];
    }
}
