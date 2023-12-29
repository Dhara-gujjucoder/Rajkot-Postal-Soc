<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // :rfc,dns
        return [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email|max:250|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'company' => 'required',
            'salary'=> 'required',
            'designation' => 'required',
            'birthdate' => 'required', 
            'mobile_no' => 'required|unique:members,mobile_no',
            'whatsapp_no' => 'numeric',
            'aadhar_card_no' => 'required',
            'aadhar_card' => 'required',
            'pan_no' => 'required',
            'pan_card' => 'required',
            'current_address' => 'required',
            'parmenant_address' => 'required',
            'nominee_name' => 'required',
            'nominee_relation' => 'required',
            'registration_no' => 'required|min:8|unique:members,registration_no',
            'saving_account_no' => 'nullable|numeric',
            'bank_name' => 'nullable',
            'ifsc_code' =>'nullable',
            'branch_address' =>'nullable',
        ];
    }
}
