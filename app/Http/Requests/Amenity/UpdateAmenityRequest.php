<?php

namespace App\Http\Requests\Amenity;

use App\Http\Requests\ResponseRequest;

use Illuminate\Validation\Rule;

use App\Models\Amenity\Amenity;

class UpdateAmenityRequest extends ResponseRequest
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
        $amenity = Amenity::where('reference_number', $this->referenceNumber)->firstOrFail();

        return [
            'name' => ['required', 'string', Rule::unique('amenities', 'name')->ignore($amenity->id)]
        ];
    }
}
