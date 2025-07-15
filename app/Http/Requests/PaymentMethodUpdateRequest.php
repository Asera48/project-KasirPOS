<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentMethodUpdateRequest extends FormRequest
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

    /**
     * Get the custom validation messages for the defined rules.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        $paymentMethodId = $this->route('payment_method')->id;

        return [
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('payment_methods')->ignore($paymentMethodId)],
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'qr_code_image' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:1024',
            'delete_qr_code' => 'nullable|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'code.unique' => 'Kode ini sudah digunakan. Harap gunakan kode unik lain.',
            'code.alpha_dash' => 'Kode hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
