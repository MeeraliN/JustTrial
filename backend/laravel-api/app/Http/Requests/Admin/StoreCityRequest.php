<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends FormRequest
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
        $cityId = $this->route('city')?->id ?? $this->route('city');

        return [
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('cities', 'name')->where(fn ($query) => $query
                    ->where('state_name', $this->input('state_name'))
                    ->where('country_name', $this->input('country_name', 'India'))
                )->ignore($cityId),
            ],
            'state_name' => ['required', 'string', 'max:120'],
            'country_name' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('country_name')) {
            $this->merge(['country_name' => 'India']);
        }
    }
}
