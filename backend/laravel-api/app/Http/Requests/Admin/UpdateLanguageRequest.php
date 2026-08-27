<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
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
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>|string>
     */
    public function rules(): array
    {
        $languageId = $this->route('language')?->id ?? $this->route('language');

        return [
            'code' => ['sometimes', 'required', 'string', 'max:10', Rule::unique('languages', 'code')->ignore($languageId)],
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'native_name' => ['sometimes', 'required', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
            'is_enabled' => ['nullable', 'boolean'],
        ];
    }
}
