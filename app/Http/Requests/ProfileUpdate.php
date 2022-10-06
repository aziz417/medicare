<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->user()->isAdmin() ? $this->adminRules() : $this->patientRules();
        return $rules;
    }

    public function adminRules()
    {
        $userId = $this->user()->id;
        return [
            'user_name' => 'required|string|max:255',
            'user_email' => "required|email|unique:users,email,{$userId}",
            'user_mobile' => "required|string|unique:users,mobile,{$userId}",
            'avatar' => 'nullable|image|max:512',
            'signature' => 'sometimes|nullable|image|max:512',
            'user_department_id' => 'sometimes|required|string|max:255',
            // Meta Data
            'meta_designation' => 'sometimes|required|string|max:255',
            'meta_about' => 'nullable|string',
            // Relations
            'charge_booking' => 'sometimes|required|numeric',
            'charge_reappoint' => 'sometimes|required|numeric',
            'charge_report' => 'sometimes|required|numeric',
        ];
    }

    public function patientRules()
    {
        $userId = $this->user()->id;
        return [
            'user_name' => 'required|string|max:255',
            'user_email' => "required|email|unique:users,email,{$userId}",
            'user_mobile' => "required|string|unique:users,mobile,{$userId}|mobile",
            'avatar' => 'nullable|image|max:512'
        ];
    }
}
