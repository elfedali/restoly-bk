<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryUpdateRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'slug' => ['required', 'alpha_dash', 'min:5', 'max:255', Rule::unique('countries')->ignore($this->country)],
            //'is_active' => ['string'],
            'lang' => ['required', 'string', Rule::in(['fr', 'ar', 'en', 'es'])],
            'currency' => ['string'],
            'currency_symbol' => ['string'],
        ];
    }
}
