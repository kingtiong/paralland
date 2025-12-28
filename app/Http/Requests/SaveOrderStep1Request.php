<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOrderStep1Request extends FormRequest
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
            'website_type' => ['required', 'string', 'in:company,personal'],
            'purpose' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:4000'],
            'address' => ['nullable', 'string', 'max:500'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'industry' => ['nullable', 'string', 'max:100'],
            'products_services' => ['nullable', 'string', 'max:4000'],
        ];
    }
}
