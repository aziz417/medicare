<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "appointment_id" => "sometimes|required",
            "patient_id" => "sometimes|required",
            "advice" => "required|string",
            "status" => "required|string",
            "chief_complain" => "required|string",
            "medicines" => "required|array",
            'medicines.*' => 'required|string',
            "type" => "required|array",
            'type.*' => 'required|string',
            "quantity" => "nullable|array",
            "days" => "nullable|array",
            "instruction" => "required|array",
            'instruction.*' => 'required|string',
            "diagnosis_title" => "nullable|array",
            'diagnosis_title.*' => 'required|string',
            "diagnosis_details" => "nullable|array",
        ];
    }
}
