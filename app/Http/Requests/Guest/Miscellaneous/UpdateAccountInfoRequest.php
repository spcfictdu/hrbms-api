<?php

namespace App\Http\Requests\Guest\Miscellaneous;

use App\Http\Requests\ResponseRequest;

class UpdateAccountInfoRequest extends ResponseRequest
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
            'email' => 'required|email|unique:users,email',
            'phoneNumber' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
        ];
    }
}
