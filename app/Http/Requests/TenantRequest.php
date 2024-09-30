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
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'logo' => 'image|mimes:jpg,jpeg,png|max:2048',
            'tagline' => 'nullable|string|max:255',
            'status' => 'required|in:5,10',
            'website' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
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
            'phone.required' => 'The phone field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'logo.image' => 'The logo must be an image.',
            'username.required' => 'The username field is required.',
            'username.unique' => 'This username is already taken, please choose another one.',
            'status.required' => 'The status is required and must be 5 or 10.',
        ];
    }
}