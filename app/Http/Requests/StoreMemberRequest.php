<?php

namespace App\Http\Requests;

use App\Models\ShareAmount;
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
            'reg_date'            => 'required',
            'name'                => 'required|string|max:250',
            'email'               => 'required|string|email|max:250|unique:users,email',
            'password'            => 'string|min:4',
            'department_id'       => 'required',
            // 'company'             => 'required',   temp
            // 'salary'              => 'required',   temp
            // 'designation'         => 'required',   temp
            'birthdate'           => 'required',
            'mobile_no'           => 'required|min:10|unique:members,mobile_no',
            'whatsapp_no'         => 'required|integer|min:10',
            'aadhar_card_no'      => 'required',
            'aadhar_card'         => 'required',
            // 'pan_no'              => 'required',
            // 'pan_card'            => 'required',
            'current_address'     => 'required',
            'parmenant_address'   => 'required',
            'nominee_name'        => 'required',
            // 'nominee_relation'    => 'required',   temp
            'registration_no'     => 'required|min:8|unique:members,registration_no',
            'saving_account_no'   => 'nullable|numeric',
            'bank_name'           => 'nullable',
            'ifsc_code'           => 'nullable',
            'branch_address'      => 'nullable',
            'total_share'         => 'required|numeric|gte:'.current_share_amount()->minimum_share,
            'profile_picture'     => 'max:2048',
            'signature'           => 'max:2048',
            'aadhar_card'         => 'max:2048',
            'pan_card'            => 'max:2048',
            'department_id_proof' => 'max:2048',
            'witness_signature'   => 'max:2048',
        ];
    }
}
