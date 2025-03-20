<?php

namespace App\Http\Requests\CashierSession;

use App\Http\Requests\ResponseRequest;

class CreateCashierSessionRequest extends ResponseRequest
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
            'openingBalance' => 'required|numeric|min:0',
            // 'closingBalance' => 'nullable|numeric',
            // 'opened_at'
        ];
    }
}
