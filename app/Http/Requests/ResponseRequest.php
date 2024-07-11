<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator,
    Illuminate\Foundation\Http\FormRequest,
    Illuminate\Http\Exceptions\HttpResponseException,
    Illuminate\Auth\Access\AuthorizationException;
/**
 * use App\Http\Requests\ResponseRequest
 * Extend this to your Requests instead of FormRequest in order to use
 */

abstract class ResponseRequest extends FormRequest
{
    //*********************************************** EXCEPTION ***********************************************//
    /**
     * Determine if user authorized to make this request
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * If authorization fails return the exception in json form
     * @return array
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json(
            [
                'message'   =>  "You have no permission to access the following data",
                'results'   =>  [],
                'code'      =>  403,
                'errors'    =>  true,
            ],
            403));
    }

    /**
     * If validator fails return the exception in json form
     * @param Validator $validator
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = [];
        $allErrorMessages = collect($validator->errors()->all())->join(' ');
        $firstErrorMessage = collect($validator->errors()->all())->first();

        foreach ($validator->errors()->messages() as $key => $value) {
            $errors[$key] = $value[0];
        }

        throw new HttpResponseException(response()->json(
            [
                'message'   => $firstErrorMessage,  // Use the first error message
                'results'   => $errors,
                'code'      =>  422,
                'errors'    =>  true,
            ],
            422));
    }

    abstract public function rules();
}
