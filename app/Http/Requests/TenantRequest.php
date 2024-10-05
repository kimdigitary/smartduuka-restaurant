<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'status' => 'required|in:5,10',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tenants')->ignore($this->route('tenant')), // ignore the current tenant on update
            ],
        ];
    }

    /**
     * Customize the error messages for validation rules.
     *
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'username.required' => 'The username field is required.',
            'username.unique' => 'This username is already taken, please choose another one.',
            'status.required' => 'The status is required and must be 5 or 10.',
        ];
    }
}