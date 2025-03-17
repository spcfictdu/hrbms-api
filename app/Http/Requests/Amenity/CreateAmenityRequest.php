<?php

namespace App\Http\Requests\Amenity;

use Illuminate\Validation\Rule;
use App\Http\Requests\ResponseRequest;

class CreateAmenityRequest extends ResponseRequest
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
            'name' => ['required', 'string', Rule::unique('amenities', 'name')],
            'price' => ['nullable', 'numeric']
        ];
    }
}
