<?php

namespace App\Http\Requests\Room\RoomType;

use App\Http\Requests\ResponseRequest;

class UpdateRoomTypeRequest extends ResponseRequest
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
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'bedSize' => ['required', 'string'],
            'propertySize' => ['required', 'string'],
            'isNonSmoking' => ['required', 'boolean'],
            'balconyOrTerrace' => ['required', 'boolean'],
            'capacity' => ['required', 'integer'],
            'images' => ['nullable', 'array'],
            'images.*.add' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:5000'],
            'images.*.delete' => ['nullable', 'string'],
            'amenities' => ['nullable', 'array'],
            'amenities.*.add' => ['nullable', 'string', 'exists:amenities,name'],
            'amenities.*.delete' => ['nullable', 'string', 'exists:amenities,name']
        ];
    }
}
