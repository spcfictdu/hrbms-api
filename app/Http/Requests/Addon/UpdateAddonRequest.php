<?php

namespace App\Http\Requests\Addon;

use App\Models\Amenity\Addon;
use Illuminate\Validation\Rule;
use App\Http\Requests\ResponseRequest;

class UpdateAddonRequest extends ResponseRequest
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
        $addon = Addon::where('reference_number', $this->referenceNumber)->firstOrFail();
        
        return [
            // 'name' => ['string', Rule::unique('addons', 'name')],
            'price' => ['nullable','numeric']
        ];
    }
}