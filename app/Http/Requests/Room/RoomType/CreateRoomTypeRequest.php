<?php

namespace App\Http\Requests\Room\RoomType;

use App\Http\Requests\ResponseRequest;

class CreateRoomTypeRequest extends ResponseRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            // 'propertySize' => filter_var($this->propertySize, FILTER_VALIDATE_BOOLEAN),
            'isNonSmoking' => filter_var($this->isNonSmoking, FILTER_VALIDATE_BOOLEAN),
            'balconyOrTerrace' => filter_var($this->balconyOrTerrace, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:room_types,name'],
            'description' => ['required', 'string'],
            'bedSize' => ['required', 'string'],
            'propertySize' => ['required', 'string'],
            'isNonSmoking' => ['required', 'boolean'],
            'balconyOrTerrace' => ['required', 'boolean'],
            'capacity' => ['required', 'integer'],
            'images' => ['required', 'array'],
            'images.*' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:5000'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['nullable', 'string', 'exists:amenities,name'],
            'rates' => ['array'],
            'rates.monday' => ['required', 'numeric'],
            'rates.tuesday' => ['required', 'numeric'],
            'rates.wednesday' => ['required', 'numeric'],
            'rates.thursday' => ['required', 'numeric'],
            'rates.friday' => ['required', 'numeric'],
            'rates.saturday' => ['required', 'numeric'],
            'rates.sunday' => ['required', 'numeric'],
        ];
    }
}
