<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'amount' => ['required', 'numeric', 'min:1'],
            'beneficiary_account_number' => ['required', 'string', 'digits:10'],
            'beneficiary_name' => ['sometimes', 'string', 'max:255'],
            'beneficiary_id' => ['sometimes', 'exists:beneficiaries,id'],
            'transaction_pin' => ['required', 'string', 'digits:4'],
        ];
    }
}
