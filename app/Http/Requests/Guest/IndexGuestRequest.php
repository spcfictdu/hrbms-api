<?php

namespace App\Http\Requests\Guest;

use App\Http\Requests\ResponseRequest;

class IndexGuestRequest extends ResponseRequest    /**
 * Determine if the user is authorized to make this request.
 *
 * @return bool
 */
    public function authorize()
{
    return true;
}


{
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
            'fullName' => 'string|nullable',
            'email' => 'email|nullable',
            'phoneNumber' => 'string|nullable|min:1|max:11'
        ];
    }
}
