<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionPinRequest extends FormRequest
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
        $rules = [
            'transaction_pin' => ['required', 'string', 'digits:4'],
        ];

        // For updating PIN, require current PIN
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['current_pin'] = ['required', 'string', 'digits:4'];
            $rules['transaction_pin_confirmation'] = ['required', 'string', 'same:transaction_pin'];
        } else {
            $rules['transaction_pin_confirmation'] = ['required', 'string', 'same:transaction_pin'];
        }

        return $rules;
    }
}
