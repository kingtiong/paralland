<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOrderStep2Request extends FormRequest
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
            'intro' => ['nullable', 'string', 'max:6000'],
            'business_nature' => ['nullable', 'string', 'max:6000'],
            'advantages' => ['nullable', 'string', 'max:6000'],
            'services' => ['nullable', 'string', 'max:6000'],
            'team_members' => ['nullable', 'string', 'max:6000'],
        ];
    }
}
