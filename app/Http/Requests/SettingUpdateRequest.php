<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class SettingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:1024',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string|max:20',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'delete_logo' => 'nullable|boolean',
            'points_per_amount' => 'required|integer|min:1',
            'point_value' => 'required|integer|min:1',
        ];
    }
}
