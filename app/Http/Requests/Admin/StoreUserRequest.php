<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email:filter', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'plan_id' => ['required', 'exists:plans,id'],
            'image' => ['nullable', 'image', 'max:8192', 'mimes:jpg,png,jpeg,webp'],
            'from' => ['required', 'date', 'string'],
            'to' => ['required', 'date', 'string'],
        ];
    }
}
