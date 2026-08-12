<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'country' => $this->input('country') ?: 'Brasil',
            'state' => strtoupper((string) $this->input('state')),
            'zipcode' => preg_replace('/\D/', '', (string) $this->input('zipcode')),
        ]);
    }

    public function rules(): array
    {
        return [
            'zipcode' => ['required', 'string', 'max:10'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'size:2'],
            'country' => ['required', 'string', 'max:100'],
            'complement' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'zipcode.required' => 'O CEP é obrigatório.',
            'street.required' => 'A rua é obrigatória.',
            'number.required' => 'O número é obrigatório.',
            'city.required' => 'A cidade é obrigatória.',
            'state.required' => 'O estado é obrigatório.',
            'state.size' => 'Informe a UF com 2 letras.',
            'country.required' => 'O país é obrigatório.',
        ];
    }
}
