<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'owner_id' => ['required', 'integer', 'exists:owners,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'street_id' => ['nullable', 'integer', 'exists:streets,id'],
            'address' => ['nullable', 'string'],
            'approved_by' => ['nullable'],
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'website' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required'],
            'is_verified' => ['required'],
            'is_featured' => ['required'],
            'longitude' => ['nullable', 'numeric'],
            'latitude' => ['nullable', 'numeric'],
        ];
    }
}
