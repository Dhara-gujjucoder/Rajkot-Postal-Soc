<?php

namespace App\Http\Requests;

use App\Models\ShareAmount;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit-user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $max_share = collect([current_share_amount()->minimum_share, $this->member->total_share])->max();
        $max_share = (int)$max_share;
        //email:rfc,dns
        return [
            // 'reg_date' => 'required',
            'name' => 'required|string|max:250',
            'email' => 'required|string|max:250|unique:users,email,' . $this->member->user->id,
            'department_id' => 'required',
            'company' => 'required',
            'salary'=> 'required',
            'designation' => 'required',
            'birthdate' => 'required',
            'whatsapp_no' => 'numeric',
            'aadhar_card_no' => 'required',
            // 'pan_no' => 'required',
            'mobile_no' => 'required|unique:members,mobile_no,' . $this->member->id,
            'current_address' => 'required',
            'parmenant_address' => 'required',
            'nominee_name' => 'required',
            'nominee_relation' => 'required',
            'registration_no' => 'required|min:8|unique:members,registration_no,' . $this->member->id,
            'saving_account_no' => 'nullable|numeric',
            'bank_name' => 'nullable|string',
            'ifsc_code' =>'nullable',
            'branch_address' =>'nullable',
            // 'minimum_share' => 'required|numeric|gt:'.current_share_amount()->minimum_share,
            'total_share' => 'required|numeric|gte:'. $max_share,
            'profile_picture' => 'max:2048',
            'signature' => 'max:2048',
            'aadhar_card' => 'max:2048',
            // 'pan_card' => 'max:2048',
            'department_id_proof' => 'max:2048',
            'witness_signature' => 'max:2048',
        ];
    }
}
