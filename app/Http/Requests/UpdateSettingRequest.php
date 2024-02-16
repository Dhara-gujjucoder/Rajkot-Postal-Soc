<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit-setting');
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
            'title' => 'required|string|max:250',
            'logo' => 'mimes:jpeg,jpg,png,gif|max:10000',
            'favicon' =>  'mimes:jpeg,jpg,png,gif|max:10000',
            'email' => 'required|string|max:250',
            'phone' => 'required|string|max:250',
            'address' => 'nullable|string',
            'name' => 'nullable|string|max:250',
            'email' => 'nullable|string|email|max:250',
        ];
    }
}
