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
            'capacity' => ['required', 'integer', 'min:1'],
            'extraPersonCapacity' => ['nullable', 'integer', 'min:0'],
            'images' => ['required', 'array', 'min:1', 'max:4'],
            'images.*' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:10000'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['nullable', 'string', 'exists:amenities,name'],
            'rates' => ['array'],
            'rates.monday' => ['required', 'numeric', 'min:0'],
            'rates.tuesday' => ['required', 'numeric', 'min:0'],
            'rates.wednesday' => ['required', 'numeric', 'min:0'],
            'rates.thursday' => ['required', 'numeric', 'min:0'],
            'rates.friday' => ['required', 'numeric', 'min:0'],
            'rates.saturday' => ['required', 'numeric', 'min:0'],
            'rates.sunday' => ['required', 'numeric', 'min:0'],
        ];
    }
}
