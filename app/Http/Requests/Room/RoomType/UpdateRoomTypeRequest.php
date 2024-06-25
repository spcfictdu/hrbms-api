<?php

namespace App\Http\Requests\Room\RoomType;

use App\Http\Requests\ResponseRequest;

use Illuminate\Validation\Rule;

use App\Models\Room\RoomType;

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
        $roomType = RoomType::where('reference_number', $this->referenceNumber)->firstOrFail();

        return [
            'name' => ['required', 'string', Rule::unique('room_types', 'name')->ignore($roomType->id)],
            'description' => ['required', 'string'],
            'bedSize' => ['required', 'string'],
            'propertySize' => ['required', 'string'],
            'isNonSmoking' => ['required', 'boolean'],
            'balconyOrTerrace' => ['required', 'boolean'],
            'capacity' => ['required', 'integer'],
            'images' => ['nullable', 'array'],

            // Arrays
            'images.add.*' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:5000'],
            'images.delete.*' => ['nullable', 'string', 'exists:room_type_images,filename'],

            // Images Update
            'images.update.new.*' => ['nullable', 'mimes:jpg,png,jpeg', 'max:5000'],
            'images.update.old.*' => ['nullable', 'string', 'exists:room_type_images,filename'],

            'amenities' => ['nullable', 'array'],
            // Arrays
            'amenities.add.*' => ['nullable', 'string', 'exists:amenities,name'],
            'amenities.delete.*' => ['nullable', 'string', 'exists:amenities,name']
        ];
    }
}
