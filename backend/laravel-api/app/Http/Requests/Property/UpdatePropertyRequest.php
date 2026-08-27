<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropertyRequest extends FormRequest
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
        return [
            'title' => ['sometimes', 'required', 'string', 'max:191'],
            'description' => ['sometimes', 'required', 'string'],
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'property_type' => ['sometimes', 'required', Rule::in(['house', 'flat', 'room', 'pg', 'hostel', 'shop', 'office'])],
            'rent_amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'maintenance_amount' => ['nullable', 'numeric', 'min:0'],
            'currency_code' => ['sometimes', 'required', 'string', 'max:10'],
            'bhk' => ['nullable', 'integer', 'min:0', 'max:20'],
            'bedrooms' => ['nullable', 'integer', 'min:0', 'max:50'],
            'bathrooms' => ['nullable', 'integer', 'min:0', 'max:50'],
            'size_sqft' => ['nullable', 'numeric', 'min:0'],
            'furnishing' => ['nullable', Rule::in(['unfurnished', 'semi_furnished', 'fully_furnished'])],
            'floor_number' => ['nullable', 'integer'],
            'total_floors' => ['nullable', 'integer'],
            'parking_spots' => ['nullable', 'integer', 'min:0'],
            'preferred_tenant' => ['nullable', Rule::in(['any', 'family', 'bachelor_male', 'bachelor_female', 'students', 'working_professionals'])],
            'available_from' => ['nullable', 'date'],
            'address_line1' => ['sometimes', 'required', 'string', 'max:191'],
            'address_line2' => ['nullable', 'string', 'max:191'],
            'city_id' => ['sometimes', 'required', 'integer', 'exists:cities,id'],
            'locality_id' => ['nullable', 'integer', 'exists:localities,id'],
            'pincode' => ['nullable', 'string', 'max:12'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'location_precision' => ['nullable', Rule::in(['exact', 'approximate'])],
            'house_rules' => ['nullable', 'string'],
            'is_owner_verified' => ['nullable', 'boolean'],
            'is_agent_listing' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['draft', 'pending', 'active', 'rejected', 'paused', 'rented', 'expired'])],
            'rejection_reason' => ['nullable', 'string'],
        ];
    }
}
