<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartFinishRequest extends FormRequest
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
            'cart' => 'required|array|min:1',
            'cart.*.productId' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'addressId' => 'required|exists:addresses,id',
        ];
    }
}
