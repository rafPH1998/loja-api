<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartMountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $ids = $this->input('ids', []);

        if (is_string($ids)) {
            $ids = array_filter(array_map('intval', explode(',', $ids)));
        }

        $this->merge([
            'ids' => array_values(array_filter((array) $ids)),
        ]);
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ];
    }
}
