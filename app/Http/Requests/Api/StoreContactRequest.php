<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:100'],
            'company' => ['nullable', 'string', 'max:100'],
            'associated_card_id' => ['nullable', 'exists:cards,id'],
            'department' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // Emails
            'emails' => ['nullable', 'array'],
            'emails.*.email' => ['required', 'email', 'max:255'],
            'emails.*.label' => ['nullable', 'string', 'max:100'],

            // Phones
            'phones' => ['nullable', 'array'],
            'phones.*.phone' => ['required', 'string', 'max:50'],
            'phones.*.ext' => ['nullable', 'string', 'max:20'],
            'phones.*.label' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated(), [
            'is_active' => true,
        ]);
    }

}
