<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeneficiaryRequest extends FormRequest
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
            'beneficiary_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'digits:10'],
            'bank_name' => ['required', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
