<?php

namespace App\Http\Requests\ReportGeneration;

use App\Http\Requests\ResponseRequest;

class GeneratePaymentReportRequest extends ResponseRequest
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
            'dateFrom' => ['nullable', 'date_format:Y-m-d'],
            'dateTo' => ['nullable', 'date_format:Y-m-d'],
            'paymentType' => ['nullable', 'exists:payments,payment_type']
        ];
    }
}