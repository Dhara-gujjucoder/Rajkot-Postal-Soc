<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'notification_email' => 'required|email',
        ];
    }
}
