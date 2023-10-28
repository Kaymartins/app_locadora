<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTypeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|' . Rule::unique('types')->ignore($this->type),
            'picture' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'doors_number' => 'required|integer',
            'seats_number' => 'required|integer',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
            'brand_id' => 'required|exists:brands,id',
        ];
    }
}
