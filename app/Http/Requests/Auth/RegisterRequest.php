<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ResponseRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends ResponseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        return $this->user()->hasRole('ADMIN');
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
            'username' => ['string', 'unique:users,username'],
            'firstName' => ['required', 'string'],
            'lastName' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'mobile' => 'nullable|string|unique:users,mobile|min:11|max:11|regex:/^09\d{9}$/',
            'password' => ['required', Password::min(8)],
            'role' => ['required', 'string', 'in:ADMIN,FRONT DESK,GUEST'],
        ];
    }
}
