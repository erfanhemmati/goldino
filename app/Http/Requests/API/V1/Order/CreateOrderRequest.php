<?php

namespace App\Http\Requests\API\V1\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'base_coin_id' => 'required|integer|exists:coins,id',
            'quote_coin_id' => 'required|integer|exists:coins,id',
            'type' => 'required|string|in:BUY,SELL',
            'amount' => 'required|numeric|min:0.000001',
            'price' => 'required|numeric|min:0'
        ];
    }
}
